<?php

namespace App\Attendize\Services;

use App\Http\Requests\Request;

class MessageAttendeeService
{
    public function make(Request $request, $attendeeId)
    {
        $attendee = $this->attendeeRepository->find($attendeeId);

        $data = [
            'attendee' => $attendee,
            'message_content' => $request->get('message'),
            'subject' => $request->get('subject'),
            'event' => $attendee->event,
            'email_logo' => $attendee->event->organiser->full_logo_path,
        ];

        Mail::send('Emails.messageReceived', $data, function ($message) use ($attendee, $data) {
            $message->to($attendee->email, $attendee->full_name)
                ->from(config('attendize.outgoing_email_noreply'), $attendee->event->organiser->name)
                ->replyTo($attendee->event->organiser->email, $attendee->event->organiser->name)
                ->subject($data['subject']);
        });

        /* Could bcc in the above? */
        if ($request->get('send_copy') == '1') {
            Mail::send('Emails.messageReceived', $data, function ($message) use ($attendee, $data) {
                $message->to($attendee->event->organiser->email, $attendee->event->organiser->name)
                    ->from(config('attendize.outgoing_email_noreply'), $attendee->event->organiser->name)
                    ->replyTo($attendee->event->organiser->email, $attendee->event->organiser->name)
                    ->subject($data['subject'] . '[ORGANISER COPY]');
            });
        }

        return true;
    }
}