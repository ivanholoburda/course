import React, { useState } from "react";
import axios from "axios";
import "./Index.css";
import { router } from "@inertiajs/react";

export default function Index({ user }) {
    const [editMode, setEditMode] = useState(false);
    const [form, setForm] = useState({
        name: user.data.name,
        surname: user.data.surname || "",
        email: user.data.email,
    });

    const [avatar, setAvatar] = useState(null);
    const [avatarUrl, setAvatarUrl] = useState(
        user.data.avatar || "/default-avatar.jpg"
    );
    const [errors, setErrors] = useState({});
    const [isSubmitting, setIsSubmitting] = useState(false);

    const handleChange = (e) => {
        setForm({ ...form, [e.target.name]: e.target.value });
    };

    const handleLogout = () => {
        router.post("/logout");
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setIsSubmitting(true);
        setErrors({});

        try {
            await axios.put("/profile", form);
            setEditMode(false);
        } catch (err) {
            if (err.response?.status === 422) {
                setErrors(err.response.data.errors || {});
            }
        } finally {
            setIsSubmitting(false);
        }
    };

    const handleAvatarChange = (e) => {
        const file = e.target.files[0];
        setAvatar(file);
        if (file) {
            const reader = new FileReader();
            reader.onloadend = () => {
                setAvatarUrl(reader.result);
            };
            reader.readAsDataURL(file);
        }
    };

    const uploadAvatar = async (e) => {
        e.preventDefault();
        if (!avatar) return;

        const formData = new FormData();
        formData.append("avatar", avatar);

        try {
            await axios.post("/profile/upload-avatar", formData, {
                headers: { "Content-Type": "multipart/form-data" },
            });
        } catch (err) {
            if (err.response?.status === 422) {
                setErrors(err.response.data.errors || {});
            }
        } finally {
            alert("Avatar uploaded successfully!");
        }
    };

    return (
        <>
            <header className="header">
                <div className="menu">
                    <div className="menuHide">
                        <i className="fa-solid fa-leaf leaf"></i>
                        <div className="skinCare" id="main">
                            SkinCare
                        </div>
                        <div className="menuChoiser">
                            <a className="holovna" href="/home">
                                Головна
                            </a>
                            <a className="tovary" href="#tovary">
                                Ваші товари
                            </a>
                            <a className="aboutUs" href="#aboutUs">
                                Про нас
                            </a>

                            <button className="myChois">Мій догляд</button>
                            <button className="account">Акаунт</button>
                        </div>

                    </div>
                </div>
            </header>

            <main className="profileMain">
                <div className="profileCard">
                    <img src={avatarUrl} alt="User" className="profilePhoto" />

                    <form onSubmit={uploadAvatar} className="avatarUploadForm">
                        <input
                            type="file"
                            accept="image/*"
                            onChange={handleAvatarChange}
                        />
                        {errors.avatar && (
                            <div className="error">{errors.avatar[0]}</div>
                        )}
                        <button type="submit" className="saveBtn">
                            Завантажити аватар
                        </button>
                    </form>

                    {!editMode ? (
                        <div className="profileInfo">
                            <h2>
                                {form.name} {form.surname}
                            </h2>
                            <p>{form.email}</p>
                            <div className="profileActions">
                                <button
                                    onClick={() => setEditMode(true)}
                                    className="editBtn"
                                >
                                    <i className="fa-solid fa-gear settings"></i>{" "}
                                    Редагувати
                                </button>
                                <button
                                    onClick={handleLogout}
                                    className="logoutBtn"
                                >
                                    Log out
                                </button>
                            </div>
                        </div>
                    ) : (
                        <form onSubmit={handleSubmit} className="editForm">
                            <input
                                type="text"
                                name="name"
                                value={form.name}
                                onChange={handleChange}
                                placeholder="Ім'я"
                            />
                            {errors.name && (
                                <div className="error">{errors.name[0]}</div>
                            )}
                            <input
                                type="text"
                                name="surname"
                                value={form.surname}
                                onChange={handleChange}
                                placeholder="Прізвище"
                            />
                            {errors.surname && (
                                <div className="error">{errors.surname[0]}</div>
                            )}
                            <input
                                type="email"
                                name="email"
                                value={form.email}
                                onChange={handleChange}
                                placeholder="Email"
                            />
                            {errors.email && (
                                <div className="error">{errors.email[0]}</div>
                            )}
                            <div className="formActions">
                                <button
                                    type="submit"
                                    className="saveBtn"
                                    disabled={isSubmitting}
                                >
                                    {isSubmitting
                                        ? "Збереження..."
                                        : "Зберегти"}
                                </button>
                                <button
                                    type="button"
                                    className="cancelBtn"
                                    onClick={() => setEditMode(false)}
                                >
                                    Скасувати
                                </button>
                            </div>
                        </form>
                    )}
                </div>
            </main>

            <footer className="footer">
                <div className="footerInclude">
                    <div className="footerLogo">
                        <i className="fa-solid fa-leaf leaf2"></i>
                        <div className="footerSKin">SkinCare</div>
                    </div>
                    <div className="footerHide">
                        <a className="fMain" href="#main">
                            Головна
                        </a>
                        <a className="fTovary" href="#tovary">
                            Товари
                        </a>
                        <a className="fAbout" href="#aboutUs">
                            Про нас
                        </a>
                        <a className="fQA">Q&A</a>
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
        </>
    );
}
