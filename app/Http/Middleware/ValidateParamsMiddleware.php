<?php

namespace App\Http\Middleware;

use App\Http\Annotations\ValidateRequestParams;
use App\Models\utils\JsonResponse;
use Closure;
use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;

class ValidateParamsMiddleware
{
    /**
     * @throws ReflectionException
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
                    $value = $request->input($field,'');
                    $violations = $validator->validate($value, $this->buildConstraints($constraints));

                    if (count($violations) > 0) {
                        foreach ($violations as $violation) {
                            $fieldErrorMessages = $errorMessages[$field] ?? [];
                            $constraintClass = get_class($violation->getConstraint());
                            $errorMessage = $fieldErrorMessages[trim(mb_substr($constraintClass,strrpos($constraintClass,'\\')),'\\')] ?? $violation->getMessage();
                            $errors[] = $field.' '.$errorMessage;
                        }
                    }
                }
                if (!empty($errors)) {
                    return JsonResponse::error(['errors' => $errors], 400);
                }
            }
        }

        return $next($request);
    }

    /**
     * @throws ReflectionException
     */
    private function buildConstraints($constraints): array
    {
        $builtConstraints = [];

        foreach ($constraints as $constraint) {
            $constraintName = ucfirst($constraint);
            $constraintOptions = [];

            if (is_array($constraint)) {
                $constraintName = ucfirst(key($constraint));
                $constraintOptions = $constraint[$constraintName];
            }else{
                if (preg_match('/(?<=\\()\\S+(?=\\))/',str_replace(' ', '', $constraintName),$match)){
                    if (count($match)){
                        foreach (explode(',',$match[0]) as $content) {
                            list($key, $value) = explode("=", $content);
                            $constraintOptions[$key] = $value;
                        }
                    }
                }
            }

            if (str_starts_with($constraintName,'Length(')) {
                $constraintOptions['min'] =(int) $constraintOptions['min'] ?? null;
                $constraintOptions['max'] =(int) $constraintOptions['max'] ?? null;
                $builtConstraints=[ new Length($constraintOptions),new NotBlank()];
                $class = get_class(new Length($constraintOptions));
            }else{
                $class = 'Symfony\Component\Validator\Constraints\\' . $constraintName;
                $reflectionClass = new ReflectionClass($class);
                $builtConstraints[] = $reflectionClass->newInstanceArgs($constraintOptions);
            }
            if (!class_exists($class)) {
                throw new \Exception('类无法找不到，无法参与验证');
            }
        }

        return $builtConstraints;
    }
}
