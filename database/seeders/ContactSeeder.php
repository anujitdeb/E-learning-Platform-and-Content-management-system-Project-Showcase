<?php

namespace Database\Seeders;

use App\Models\Contact\Contact;
use App\Models\Contact\ContactContent;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contact = Contact::updateOrCreate([
            'created_by' => 1,
            'whats_app_link' => 'https://www.creativeitinstitute.com/contact-us',
            'messenger_link' => 'https://www.creativeitinstitute.com/contact-us',
            'hotline_number' => '+880 1777308777',
            'icon' => 'seeder-images/facility-icon.png',
            'is_active' => true,
        ]);
        ContactContent::updateOrCreate([
            'contact_id' => $contact->id,
            'language_id' => 1,
            'title' => 'Contact Us',
            'description' => 'You are welcome to visit our office for any information related to course and training. You can also reach us through the hotline number or messenger.',
            'btn_name' => 'Explore',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        ContactContent::updateOrCreate([
            'contact_id' => $contact->id,
            'language_id' => 2,
            'title' => 'আমাদের সাথে যোগাযোগ করুন',
            'description' => 'কোর্স এবং প্রশিক্ষণ সম্পর্কিত যেকোনো তথ্যের জন্য আমাদের অফিসে যেতে আপনাকে স্বাগতম। আপনি হটলাইন নম্বর বা মেসেঞ্জারের মাধ্যমেও আমাদের সাথে যোগাযোগ করতে পারেন।',
            'btn_name' => 'অন্বেষণ',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
