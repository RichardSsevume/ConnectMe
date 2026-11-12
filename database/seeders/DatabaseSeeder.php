<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Demonstration data. Change or delete these accounts before real use.
     *   Admin     admin@connectme.com      / Admin@12345
     *   Employers employer1@connectme.com  / Employer@12345   (employer2@connectme.com too)
     *   Youth     aisha@connectme.com      / Youth@12345      (brian@, grace@, moses@ too)
     */
    public function run(): void
    {
        User::create(['name' => 'Connect Me Admin', 'email' => 'admin@connectme.com', 'password' => 'Admin@12345',
            'role' => 'admin', 'verification_status' => 'verified', 'phone' => '+256 700 000 000', 'location' => 'Kampala']);

        $e1 = User::create(['name' => 'Sarah Nambi', 'email' => 'employer1@connectme.com', 'password' => 'Employer@12345',
            'role' => 'employer', 'company_name' => 'Kampala Tech Solutions Ltd', 'industry' => 'Information Technology',
            'company_description' => 'We build software and provide IT support to small and medium businesses across Uganda.',
            'phone' => '0700111222', 'location' => 'Kampala', 'website' => 'https://example.com', 'verification_status' => 'verified']);

        $e2 = User::create(['name' => 'David Mugisha', 'email' => 'employer2@connectme.com', 'password' => 'Employer@12345',
            'role' => 'employer', 'company_name' => 'Nile Hospitality Group', 'industry' => 'Hospitality & Tourism',
            'company_description' => 'Hotels and restaurants in Kampala and Jinja.',
            'phone' => '0700333444', 'location' => 'Jinja', 'verification_status' => 'pending']);

        $youth = [
            ['Aisha Namutebi', 'aisha@connectme.com', 'Graphic Designer', 'Graphic design, Canva, Photoshop, Logo design', 'Diploma', 'assets/img/14.jpeg', 'verified', true],
            ['Brian Okello', 'brian@connectme.com', 'Junior Web Developer', 'HTML, CSS, JavaScript, Laravel, WordPress', "Bachelor's Degree", 'assets/img/15.jpeg', 'verified', true],
            ['Grace Nakato', 'grace@connectme.com', 'Social Media Manager', 'Digital marketing, Content writing, Social media', 'Certificate', 'assets/img/12.jpeg', 'pending', true],
            ['Moses Ssebugwawo', 'moses@connectme.com', 'Data Entry Clerk', 'Data entry, Microsoft Excel, Typing', 'Diploma', 'assets/img/5.jpeg', 'pending', false],
        ];

        $people = [];
        foreach ($youth as $i => [$name, $email, $title, $skills, $edu, $photo, $verification, $visible]) {
            $people[] = User::create([
                'name' => $name, 'email' => $email, 'password' => 'Youth@12345', 'role' => 'youth',
                'phone' => '07000000' . ($i + 11), 'location' => $i === 2 ? 'Wakiso' : 'Kampala',
                'professional_title' => $title, 'skills' => $skills, 'education_level' => $edu,
                'bio' => 'Trained in digital and professional skills and looking for my next opportunity.',
                'work_experience' => 'Short-term projects, internships and volunteer work.',
                'profile_photo' => $photo, 'verification_status' => $verification, 'visible_to_employers' => $visible,
            ]);
        }

        $make = fn ($emp, $title, $cat, $slots, $days, $skills, $type, $loc, $status = 'open') => Vacancy::create([
            'employer_id' => $emp->id, 'title' => $title, 'category' => $cat, 'slots' => $slots,
            'description' => "We are looking for a motivated {$title} to join our team. You will work with experienced colleagues, learn quickly and take responsibility for your tasks.",
            'required_skills' => $skills, 'qualifications' => 'Certificate or Diploma in a related field',
            'experience' => '0 - 2 years', 'location' => $loc, 'employment_type' => $type,
            'deadline' => now()->addDays($days), 'contact_info' => $emp->email . ' / ' . $emp->phone, 'status' => $status,
        ]);

        $accountant = $make($e1, 'Accountant', 'Finance & Accounting', 3, 30, 'Bookkeeping, Excel, Reporting', 'Full-time', 'Kampala');
        $support = $make($e1, 'IT Support', 'Information Technology', 2, 21, 'Troubleshooting, Networking, Customer service', 'Full-time', 'Kampala');
        $analyst = $make($e1, 'Data Analyst', 'Information Technology', 1, 25, 'Excel, Power BI, SQL', 'Contract', 'Kampala', 'closed');
        $sales = $make($e1, 'Sales Assistant', 'Sales & Marketing', 2, 14, 'Communication, Sales, Social media', 'Part-time', 'Kampala');
        $front = $make($e2, 'Front Desk Officer', 'Hospitality & Tourism', 1, 10, 'Customer care, English, Computer skills', 'Full-time', 'Jinja');
        $make($e1, 'Social Media Intern', 'Creative & Media', 1, 20, 'Content creation, Canva', 'Internship', 'Kampala', 'draft');

        $apply = fn ($v, $u, $status, $note) => Application::create(['vacancy_id' => $v->id, 'user_id' => $u->id, 'status' => $status, 'cover_note' => $note]);
        $apply($support, $people[1], 'approved', 'I have supported small office networks and websites.');
        $apply($sales, $people[0], 'pending', 'I have promoted local businesses on social media.');
        $apply($sales, $people[2], 'approved', 'I manage social media pages for small shops.');
        $apply($accountant, $people[3], 'pending', 'I am good with Excel and accurate data entry.');
        $apply($accountant, $people[0], 'rejected', null);
        $apply($front, $people[2], 'withdrawn', null);
    }
}
