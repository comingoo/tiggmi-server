<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use Carbon;

class Token extends Model
{
    const EXPIRATION_TIME = 15; // minutes

    protected $fillable = [
        'code',
        'customer_id',
        'used'
    ];

    public function __construct(array $attributes = [])
    {
        if (! isset($attributes['code'])) {
            $attributes['code'] = $this->generateCode();
        }

        parent::__construct($attributes);
    }

    /**
     * Generate a six digits code
     *
     * @param int $codeLength
     * @return string
     */
    public function generateCode($codeLength = 4)
    {
        $min = pow(10, $codeLength);
        $max = $min * 10 - 1;
        $code = mt_rand($min, $max);

        return $code;
    }

    /**
     * Customer tokens relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * True if the token is not used nor expired
     *
     * @return bool
     */
    public function isValid()
    {
        return ! $this->isUsed() && ! $this->isExpired();
    }

    /**
     * Is the current token used
     *
     * @return bool
     */
    public function isUsed()
    {
        return $this->used;
    }

    /**
     * Is the current token expired
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->created_at->diffInMinutes(Carbon\Carbon::now()) > static::EXPIRATION_TIME;
    }

    public function sendCode()
    {
        if (! $this->customer) {
            throw new \Exception("No user attached to this token.");
        }

        if (! $this->code) {
            $this->code = $this->generateCode(4);
           //$this->code = 1234 ;
        }

        try 
        {
            /*
                app('twilio')->messages->create($this->customer->getPhoneNumber(),
                ['from' => env('MAIL_USERNAME'), 'body' => "Your OTP code is {$this->code}"]);
            */
            // OTP send to customer email
            $subject = 'Login OTP';
            $message = "Your OTP code is {$this->code}";
            mail($this->customer->getEmail(),$subject,$message,env('MAIL_USERNAME'));
            return true;
        } 
        catch (\Exception $ex) {
            return false; //unable to send email
        }

        return true;
    }
}
