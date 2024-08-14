<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class SendingEmailRenew extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $recipientName;
    public $recipientEmail;

    public function __construct($recipientName, $recipientEmail, $bodyEmail, $mobileDetails, $coverageEnd, $filename, $orderId, $recipientbcc) {
        $this->recipientName = $recipientName;
        $this->recipientEmail = $recipientEmail;
        $this->bodyEmail = $bodyEmail;
        $this->mobileDetails = $mobileDetails;
        $this->coverageEnd = $coverageEnd; 
        $this->filename = $filename; 
        $this->orderId = $orderId; 
        $this->recipientbcc = $recipientbcc; 
    }
    

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
       // dd($this->filename);
        $pdfPath = 'pdf/'.$this->filename;
        return $this->view('emails.emailrenewscustomer')
        // return $this->view('pdf.renewcustomer')
            ->with([
                'recipientName' => $this->recipientName,
                'recipientEmail' => $this->recipientEmail,
                'mobileDetails' => $this->mobileDetails,
                'bodyEmail' => $this->bodyEmail,
                'coverageEnd' => $this->coverageEnd,
                'orderId' => $this->orderId,
            ])
            ->to($this->recipientEmail)
            ->bcc($this->recipientbcc)
            ->subject('ใบเตือนต่ออายุประกัน iPhone By iCare')
            ->attachFromstorage($pdfPath, $this->filename, [
                'as' => $this->filename,  
                'mime' => 'application/pdf',
            ]);
    }
    
    
}
