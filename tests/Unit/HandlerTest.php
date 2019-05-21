<?php

namespace Tests\Unit;

use App\Exceptions\Handler;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tests\TestCase;

class HandlerTest extends TestCase
{
    /** @test */
    public function renders_exception_as_json_if_requested()
    {
        $handler = new Handler(\Mockery::mock(Container::class));

        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('wantsJson')->andReturn(true);

        $exception = new \Exception('Exception message', 500);

        $result = $handler->render($request, $exception);

        $expectedResponse = new JsonResponse(['error' => ['message' => 'Exception message']]);
        $expectedResponse->setStatusCode(500);
        $this->assertEquals($expectedResponse, $result);
    }
}
