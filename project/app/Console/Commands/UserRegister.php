<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UserRegister extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:register';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Регистрация нового пользователя';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // получаем name
        $name = $this->ask('Введите имя пользователя');

        // получаем email
        $email = $this->getEmail();

        // получаем пароль
        $password = $this->ask('Введите пароль');

        $User = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'api_token' => Hash::make(\Illuminate\Support\Carbon::now()->toRfc2822String())
        ]);

        if ($User->id > 0) {
            $this->info(' Аккаунт успешно создан!');
            $this->info(' Добро пожаловать.');
            $this->info(' Ваш токен для доступа к АПИ:');
            $this->info(' '.$User->api_token);
            $this->info(' ');
        } else {
            $this->error(' Ошибка создания аккаунта, свяжитесь с администратором');
        }
    }

    private function getEmail()
    {
        $email = $this->ask('enter E-mail');
        $user = User::whereEmail($email)->first();

        if ($user !== null) {
            $this->error(' Данный e-mail уже используется в системе, попробуйте ввести другой e-mail');
            return $this->getEmail();
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error(' Введенный e-mail не удовлетворяет требованиям к формату e-mail, попробуйте ввести другой e-mail');
            return $this->getEmail();
        }
        return $email;
    }
}
