import React from "react";
import { useForm } from '@inertiajs/react';
import QAphoto from '@/assets/photo/QAphoto.png';
import './Index.css';

export default function Index() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        surname: '',
        email: '',
        password: '',
        password_confirmation: ''
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post('/register');
    };

    return (
        <div className="container">
            <div className="image-container">
                <img src={QAphoto} alt="cosmetics" className="signUpPhoto" />
                <a href={'/'}><i className="fa-solid fa-arrow-left arrow"></i></a>
            </div>

            <div className="registrForm">
                <div className="registr">Реєстрація</div>
                <form onSubmit={handleSubmit} className="inputRegist">
                    <input
                        type="text"
                        id="firstName"
                        placeholder="Ім'я"
                        value={data.name}
                        onChange={(e) => setData('name', e.target.value)}
                    />
                    {errors.name && <div className="error">{errors.name}</div>}

                    <input
                        type="text"
                        id="lastName"
                        placeholder="Прізвище"
                        value={data.surname}
                        onChange={(e) => setData('surname', e.target.value)}
                    />
                    {errors.surname && <div className="error">{errors.surname}</div>}

                    <input
                        type="email"
                        id="email"
                        placeholder="E-mail"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                    />
                    {errors.email && <div className="error">{errors.email}</div>}

                    <input
                        type="password"
                        id="password"
                        placeholder="Пароль"
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                    />
                    {errors.password && <div className="error">{errors.password}</div>}

                    <input
                        type="password"
                        id="repeatPassword"
                        placeholder="Підтвердження паролю"
                        value={data.password_confirmation}
                        onChange={(e) => setData('password_confirmation', e.target.value)}
                    />
                    {errors.password_confirmation && <div className="error">{errors.password_confirmation}</div>}

                    <button id="signUpBtn" type="submit" disabled={processing}>
                        {processing ? 'Реєстрація...' : 'Зареєструватись'}
                    </button>
                </form>
            </div>
        </div>
    );
}
