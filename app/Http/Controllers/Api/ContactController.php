<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ContactSuccessToCustomer;
use App\Mail\ContactToCompany;
use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use OpenApi\Attributes as OA;

class ContactController extends Controller
{
    #[OA\Post(
        path: '/api/contact',
        summary: 'Obtain contact information and send notification emails.',
        tags: ['Contact']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['category', 'name', 'email', 'content', 'agree'],
            properties: [
                new OA\Property(property: 'category', type: 'string', example: 'Hỗ trợ kỹ thuật'),
                new OA\Property(property: 'name', type: 'string', example: 'Trần Anh Quân'),
                new OA\Property(property: 'company', type: 'string', example: 'Công ty Kakusin'),
                new OA\Property(property: 'email', type: 'string', example: 'quan@example.com'),
                new OA\Property(property: 'content', type: 'string', example: 'Nội dung cần liên hệ...'),
                new OA\Property(property: 'agree', type: 'boolean', example: true),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Gửi liên hệ thành công',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'Contact information has been successfully submitted.'),
            ]
        )
    )]
    #[OA\Response(
        response: 429,
        description: 'Gửi quá giới hạn cho phép',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'error'),
                new OA\Property(property: 'message', type: 'string', example: 'Bạn đã đạt giới hạn gửi liên hệ trong ngày. Vui lòng thử lại vào ngày mai.'),
            ]
        )
    )]
    public function submitContact(Request $request)
    {
        $validatedData = $request->validate([
            'category' => 'required|string|max:150',
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'content' => 'required|string',
            'agree' => 'required|boolean',
        ]);

        $dailyRequests = Contact::where('email', $validatedData['email'])
            ->whereDate('created_at', Carbon::today())
            ->count();

        if ($dailyRequests >= 5) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have reached the daily limit for sending contact requests. Please try again tomorrow.',
            ], 429);
        }

        try {
            $dbData = Arr::except($validatedData, ['name']);

            $contact = Contact::create($dbData);

            $contact->name = $validatedData['name'];

            Mail::to(env('MAIL_RECEIVE_ADDRESS'))->send(new ContactToCompany($contact));
            Mail::to($contact->email)->send(new ContactSuccessToCustomer($contact));

            return response()->json([
                'status' => 'success',
                'message' => 'Contact information has been successfully submitted.',
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Contact Submission Error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Server error, please try again later.',
            ], 500);
        }
    }
}
