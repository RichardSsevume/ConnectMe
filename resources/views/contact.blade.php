@extends('layouts.site')
@section('title', 'Contact Us')
@section('content')
<section class="page-banner"><div class="container"><h1>Get in Touch</h1></div></section>

<section class="section">
    <div class="container">
        <p class="lead-text text-center mx-auto" style="max-width:760px">Have a question, suggestion or need assistance? We would love to hear from you. Contact the Connect Me team using the information below or send us a message.</p>
        <div class="row g-4 mt-2">
            <div class="col-lg-5">
                <div class="mv-card h-100">
                    <h2>Contact Information</h2>
                    <ul class="contact-list">
                        <li><i class="fa-solid fa-envelope"></i><div><strong>Email Address</strong><br>info@connectme.com</div></li>
                        <li><i class="fa-solid fa-phone"></i><div><strong>Phone Number</strong><br>+256 755 199 951</div></li>
                        <li><i class="fa-solid fa-location-dot"></i><div><strong>Location</strong><br>Kampala, Uganda</div></li>
                        <li><i class="fa-solid fa-clock"></i><div><strong>Working Hours</strong><br>Monday to Friday, 8:00 AM - 5:00 PM</div></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="form-card">
                    <h2>Send Us a Message</h2>
                    <form method="POST" action="{{ route('contact.send') }}">@csrf
                        <div class="mb-3"><label class="form-label" for="name">Name</label>
                            <input class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required></div>
                        <div class="mb-3"><label class="form-label" for="email">Email</label>
                            <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address" required></div>
                        <div class="mb-3"><label class="form-label" for="subject">Subject</label>
                            <input class="form-control" id="subject" name="subject" value="{{ old('subject') }}" placeholder="What is your message about?" required></div>
                        <div class="mb-3"><label class="form-label" for="message">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" placeholder="Write your message here" required>{{ old('message') }}</textarea></div>
                        <button class="btn btn-teal btn-lg">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
