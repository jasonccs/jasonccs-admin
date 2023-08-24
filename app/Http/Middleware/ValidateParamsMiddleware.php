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
                    $filed=str_replace(' ', '', $field);
                    $value = $request->input($filed);
                    $violations = $validator->validate($value, $this->buildConstraints($constraints));
                    if (count($violations)) {
                        foreach ($violations as $violation) {
                            $fieldErrorMessages = $errorMessages[$filed] ?? [];
                            $implClass = $violation->getConstraint();
                            $getClass = get_class($violation->getConstraint());
                            $errorMessage = $fieldErrorMessages[str_replace(' ','',trim(mb_substr($getClass,strrpos($getClass,'\\')),'\\'))] ?? $violation->getMessage();
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
                        if (str_starts_with($constraintName,'Length(')){ // 字符创长度判断 Length()
                            foreach (explode(',',$match[0]) as $content) {
                                list($key, $value) = explode("=", $content);
                                $constraintOptions[$key] = $value;
                            }
                        }else if (str_starts_with($constraintName,'Regex(')){
                            $constraintOptions = new Regex(['pattern' => $match[0]]);
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
                $builtConstraints[] = $constraintOptions;
                $class = get_class($constraintOptions);
            }else{
                $class = 'Symfony\Component\Validator\Constraints\\' . $constraintName;
                $reflectionClass = new ReflectionClass($class);
                $builtConstraints[] = $reflectionClass->newInstanceArgs($constraintOptions);
            }
            if (!class_exists($class)) {
                throw new \InvalidArgumentException('验证类无法找不到，无法参与验证');
            }
        }
        return $builtConstraints;
    }
}
