<?php

/**
 * Generates a JSON response with the given data, status, and message.
 *
 * @param  mixed  $data The data to be included in the JSON response.
 * @param  int  $status The status code of the response.
 * @param  string  $message The message to be included in the JSON response.
 * @return \Illuminate\Http\JsonResponse The JSON response containing the data, status, and message.
 */
function responseJSON($data, $status, $message)
{
    return response()->json([
        'status' => $status == 200 ? true : false,
        'message' => $message,
        'data' => $data,
    ], $status);
}
