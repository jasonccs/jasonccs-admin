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
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
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
                $errorMessages = array_combine(array_map(fn($key) => str_replace(' ', '', $key), array_keys($annotation->errorMessages)),array_values($annotation->errorMessages));
                $validator = Validation::createValidator();
                $errors = [];
                foreach ($rules as $field => $constraints) {
                    $value = $request->input(str_replace(' ', '', $field));
                    $violations = $validator->validate($value, $this->buildConstraints($constraints));

                    if (count($violations) > 0) {
                        foreach ($violations as $violation) {
                            $fieldErrorMessages = $errorMessages[str_replace(' ', '', $field)] ?? [];
                            $constraintClass = get_class($violation->getConstraint());
                            $errorMessage = $fieldErrorMessages[str_replace(' ','',trim(mb_substr($constraintClass,strrpos($constraintClass,'\\')),'\\'))] ?? $violation->getMessage();
                            $errors[] = str_replace(' ', '', $field).' '.$errorMessage;
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

            if (str_starts_with($constraintName,'Length(')) { // 对于 长度的范围的控制
                $constraintOptions['min'] =(int) $constraintOptions['min'] ?? null;
                $constraintOptions['max'] =(int) $constraintOptions['max'] ?? null;
                $builtConstraints[] = new Length($constraintOptions);
                $class = get_class(new Length($constraintOptions));
            }else if (str_starts_with($constraintName,'Regex(')){ // 对于正则表达式的校验
                $constraint = new Collection([
                    'name' => new Regex('/^[A-Za-z]+$/')
                ]);
                $builtConstraints[] = $constraint;
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
