<?php

namespace App\Exceptions;

// use App\Exceptions\ApiException;

class Errors
{
    public static function InternalServerError($systemMessage = 'Internal Server Error', $message = 'Internal Server Error')
    {
        throw new ApiException($message, 'INTERNAL_SERVER_ERROR', 500, $systemMessage);
    }

    public static function ResourceNotFound($systemMessage = 'Resource Not Found', $message = 'Resource Not Found')
    {
        throw new ApiException($message, 'RESOURCE_NOT_FOUND', 404, $systemMessage);
    }

    public static function ResourceAlreadyExists($systemMessage = 'Resource Already Exists', $message = 'Resource Already Exists')
    {
        throw new ApiException($message, 'RESOURCE_ALREADY_EXISTS', 400, $systemMessage);
    }

    public static function UnAcceptableOperation($systemMessage = 'Unacceptable Operation', $message = 'Unacceptable Operation')
    {
        throw new ApiException($message, 'UNACCEPTABLE_OPERATION', 400, $systemMessage);
    }

    public static function NotAuthenticated($systemMessage = 'Not Authenticated', $message = 'Not Authenticated')
    {
        throw new ApiException($message, 'NOT_AUTHENTICATED', 403, $systemMessage);
    }

    public static function NotVerified($systemMessage = 'User is not verified', $message = 'User is not verified')
    {
        throw new ApiException($message, 'NOT_VERIFIED', 400, $systemMessage);
    }

    public static function InvalidResetToken($systemMessage = 'The token used to reset is invalid', $message = 'The token used to reset is invalid')
    {
        throw new ApiException($systemMessage, 'INVALID_RESET_TOKEN', 400, $message);
    }

    public static function ResetCodeIsInvalid($systemMessage = 'The reset code is not valid', $massage = 'The reset code is not valid')
    {
        throw new ApiException($systemMessage, 'RESET_CODE_INVALID', 400, $massage);
    }

    public static function InvalidCredentials($message = 'invalid credentials', $systemMessage = 'invalid credentials')
    {
        throw new ApiException($message, 'INVALID_CREDENTIALS', 400, $systemMessage);
    }

    public static function NotAuthorized($message = 'You are not authorized', $systemMessage = 'You are not authorized')
    {
        throw new ApiException($message, 'NOT_AUTHORIZED', 403, $systemMessage);
    }

    public static function RelatedResourceExisted($message = 'There is a related resource', $systemMessage = 'There is a related resource')
    {
        throw new ApiException($message, 'RELATED_RESOURCE_EXISTED', 400, $systemMessage);
    }

    public static function TemplateNotFound($message = 'Template Not Found', $systemMessage = 'Template Not Found')
    {
        throw new ApiException($message, 'TEMPLATE_NOT_FOUND', 400, $systemMessage);
    }

    public static function InvalidCoupon($message = 'Invalid Coupon', $systemMessage = 'Invalid Coupon')
    {
        throw new ApiException($message, 'INVALID_COUPON', 400, $systemMessage);
    }

    public static function InvalidCouponValue($message = 'Invalid Coupon Value', $systemMessage = 'Invalid Coupon Value')
    {
        throw new ApiException($message, 'INVALID_COUPON_VALUE', 400, $systemMessage);
    }

    public static function InvalidOperation($message = 'Invalid Operation', $systemMessage = 'Invalid Operation')
    {
        throw new ApiException($message, 'INVALID_OPERATION', 400, $systemMessage);
    }
}
