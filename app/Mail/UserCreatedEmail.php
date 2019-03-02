<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreatedEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var User $senderUser
     */
    private $senderUser;

    /**
     * @var User $receiverUser
     */
    private $receiverUser;

    /**
     * @var array $additionalInformation
     */
    private $additionalInformation;

    /**
     * Create a new message instance.
     *
     * @param User $senderUser
     * @param User $receiverUser
     * @param array $additionalInformation|optional
     * @return void
     */
    public function __construct(User $senderUser = null, User $receiverUser = null, array $additionalInformation = [])
    {
        $this->senderUser = $senderUser;
        $this->receiverUser = $receiverUser;
        $this->additionalInformation = $additionalInformation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data['senderUser'] = $this->senderUser;
        $data['receiverUser'] = $this->receiverUser;
        $data['additionalInformation'] = $this->additionalInformation;

        return $this->view('emails.userCreated')->with($data);
    }
}
