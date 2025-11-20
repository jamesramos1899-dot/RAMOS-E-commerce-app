<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attribute\Title;
use Illuminate\Support\Facades\Password;

#[Title('Forgot Password')]
class ForgotPasswordPage extends Component
{

    public $email;
    public function save()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email|max:255',
        ]);

        // Logic to handle password reset email sending goes here

        $status = Password::sendResetLink(
            ['email' => $this->email]
        );

        if($status === Password::RESET_LINK_SENT) {
            session()->flash('success', 'Password reset link sent to your email.');
            $this->email = '';
        
        }
    }
    public function render()
    {
        return view('livewire.auth.forgot-password-page');
    }
}
