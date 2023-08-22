<?php
use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    /**
     * @var mixed|string
     */
    public mixed $method;
    /**
     * @var mixed|string
     */
    public mixed $path;
    /**
     * @var false|mixed|string
     */
    public mixed $request_data;

    public mixed $response_content;

    public mixed $request_id;

    protected $guarded = [];

    protected $fillable = ['request_id','path','method','request_data','response_content'];

}
