import React, { useState } from "react";
import { useForm } from '@inertiajs/react';
import './Homepage.css';
import mainPhoto from '@/assets/photo/mainPhoto.png';

export default function Homepage() {
    const [isModalOpen, setIsModalOpen] = useState(false);

    const openModal = () => setIsModalOpen(true);
    const closeModal = () => setIsModalOpen(false);

    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: ''
    });

    const handleLogin = (e) => {
        e.preventDefault();
        post('/login', {
            onSuccess: () => closeModal(),
        });
    };

    return (
        <>
            <header className="header">
                <div className="menu">
                    <div className="menuHide">
                        <i className="fa-solid fa-leaf leaf"></i>
                        <div className="skinCare" id="main">SkinCare</div>

                        <div className="menuButtons">
                            <button onClick={() => window.location.href = '/register'} className="registration">Реєстрація</button>
                            <button onClick={openModal} className="logIn">Вхід</button>
                        </div>
                    </div>
                </div>
            </header>

            <main className="registerMain">
                <div className="includeMain">
                    <div className="phraceMain">Скануй та слідкуй!</div>
                    <div>
                        Допоможемо вам залишатися гарними та здоровими, дбаючи про терміни
                        придатності Вашої косметики. За допомогою SkinCare ви зможете легко
                        відстежувати дати придатності кожного засобу, отримувати сповіщення
                        про наближення строку та завжди мати під контролем свої косметичні
                        засоби.
                    </div>
                    <button onClick={() => setIsModalOpen(true)} className="buttonEnter">Ввести продукт</button>
                </div>

                <img
                    src={mainPhoto}
                    alt="productScanning"
                    className="registerPhoto"
                />
            </main>

            <div className="stats-container">
                <div className="stat-card">
                    <i className="fa-solid fa-users"></i>
                    <h3>1000+</h3>
                    <p>Задоволених користувачів</p>
                </div>
                <div className="stat-card">
                    <i className="fa-solid fa-box"></i>
                    <h3>6000+</h3>
                    <p>Косметичних засобів</p>
                </div>
                <div className="stat-card">
                    <i className="fa-solid fa-star"></i>
                    <h3>95%</h3>
                    <p>Позитивних відгуків</p>
                </div>
            </div>

            <div className="howItWork">Як це працює</div>

            <div className="howItWorkInclude">
                <div>
                    <div>1. Авторизація:</div>
                    <div>Створіть власний акаунт.</div>
                </div>

                <div>
                    <div>2. Додавання продуктів:</div>
                    <div>Відскануйте штрих-код вашого засобу, або введіть вручну номер</div>
                </div>

                <div>
                    <div>3. Сповіщення:</div>
                    <div>
                        SkinCare буде нагадувати вам про важливі дати, допомагаючи уникнути
                        використання застарілих продуктів.
                    </div>
                </div>
            </div>

            <footer className="footer">
                <div className="footerInclude">
                    <div className="footerLogo">
                        <i className="fa-solid fa-leaf leaf2"></i>
                        <div className="footerSKin">SkinCare</div>
                    </div>

                    <div className="footerСonnection">
                        <div className="footerPhone">
                            <i className="fa-regular fa-phone"></i>
                            <div className="phone">+380xxxxxxxxx</div>
                        </div>

                        <div className="footerEmail">
                            <i className="fa-regular fa-envelope"></i>
                            <div className="email">skinscancare@gmail.com</div>
                        </div>
                    </div>

                    <div className="socialNet">
                        <i className="fa-brands fa-telegram telegram"></i>
                        <i className="fa-brands fa-instagram instagram"></i>
                        <i className="fa-brands fa-facebook facebook"></i>
                    </div>
                </div>
            </footer>

            {isModalOpen && (
                <>
                    <div className="modal-overlay-log" onClick={closeModal}></div>
                    <div className="modalLog">
                        <form onSubmit={handleLogin}>
                            <div className="vhid">Вхід</div>

                            <input
                                type="email"
                                placeholder="Логін"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                            />
                            {errors.email && <div className="error">{errors.email}</div>}

                            <input
                                type="password"
                                placeholder="Пароль"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                            />
                            {errors.password && <div className="error">{errors.password}</div>}

                            <button className="notLogIn" type="button" onClick={() => window.location.href = '/register'}>
                                Немає акаунту?
                            </button>

                            <button id="submitBtnLog" type="submit" disabled={processing}>
                                {processing ? 'Вхід...' : 'Увійти'}
                            </button>
                        </form>
                    </div>
                </>
            )}
        </>
    );
}
