<?php

namespace App\Http\Middleware;

use App\Http\Annotations\ValidateRequestParams;
use Closure;
use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Validator\Validation;

class ValidateParamsMiddleware
{
    /**
     * @throws \ReflectionException
     */
    public function handle($request, Closure $next)
    {
        $method = $request->route()->getActionMethod();
        $controller = $request->route()->getController();

        $reflectionMethod = new ReflectionMethod($controller, $method);
        $reader = new AnnotationReader();
        $annotations = $reader->getMethodAnnotations($reflectionMethod);

        foreach ($annotations as $annotation) {
            if ($annotation instanceof ValidateRequestParams) {
                $rules = $annotation->rules;
                $errorMessages = $annotation->errorMessages;
                $validator = Validation::createValidator();
                $errors = [];

                foreach ($rules as $field => $constraints) {
                    $value = $request->input($field);
                    $violations = $validator->validate($value, $this->buildConstraints($constraints));

                    if (count($violations) > 0) {
                        foreach ($violations as $violation) {
                            $fieldErrorMessages = $errorMessages[$field] ?? [];
                            $constraintClass = get_class($violation->getConstraint());
                            $errorMessage = $fieldErrorMessages[$constraintClass] ?? $violation->getMessage();

                            $errors[] = [
                                'field' => $field,
                                'message' => $errorMessage,
                            ];
                        }
                    }
                }
                if (!empty($errors)) {
                    return response()->json(['errors' => $errors], 422);
                }
            }
        }

        return $next($request);
    }

    private function buildConstraints($constraints): array
    {
        $builtConstraints = [];

        foreach ($constraints as $constraint) {
            $constraintName = $constraint;
            $constraintOptions = [];

            if (is_array($constraint)) {
                $constraintName = key($constraint);
                $constraintOptions = $constraint[$constraintName];
            }
            $class = 'Symfony\Component\Validator\Constraints\\' . $constraintName;
            if (class_exists($class)) {
                $reflectionClass = new ReflectionClass($class);
                $builtConstraints[] = $reflectionClass->newInstanceArgs($constraintOptions);
            }
        }

        return $builtConstraints;
    }
}
