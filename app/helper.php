<?php

function sendSuccess($message, $data)
{
    return ['status' => true, 'statusCode' => 200, 'message' => $message, 'data' => $data];
}

function sendError($message, $data = null, $statusCode = 400)
{
    return response()->json(['status' => false, 'statusCode' => $statusCode, 'message' => $message, 'data' => $data], $statusCode);
}
