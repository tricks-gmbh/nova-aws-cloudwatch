<?php

namespace Tricks\NovaAwsCloudwatch\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class ShowSingleStreamRequest extends FormRequest
{

    public function rules(): array
    {
        $rules = [
            'log_group_name' => [
                'required',
            ],
            'stream' => [
                'required'
            ]
        ];

        $onlyRules = config('nova_aws_cloudwatch.groups.only');
        if ($onlyRules !== []) {
            $rules['log_group_name'][] = Rule::in($onlyRules);
        }

        $excludeRules = config('nova_aws_cloudwatch.groups.exclude');
        if ($excludeRules !== []) {
            $rules['log_group_name'][] = Rule::notIn($excludeRules);
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator): JsonResponse
    {
        dd($validator->errors());
        return new JsonResponse(
            data: [
                'status' => 'error',
                'message' => __('LogGroupName not allowed')
            ],
            status: 403
        );
    }
}
