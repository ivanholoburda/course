import React, { useState } from "react";
import axios from "axios";
import background from "@/assets/photo/backGHome.png";
import "./Index.css";

export default function Index({ user, products }) {
    const [showModal, setShowModal] = useState(false);
    const [formData, setFormData] = useState({
        name: "",
        country: "",
        due_date: "",
        open_days: "",
        image: null,
    });
    const [userProducts, setProducts] = useState(products.data);
    const [showBarcodeModal, setShowBarcodeModal] = useState(false);
    const [barcode, setBarcode] = useState("");

    const handleBarcodeSubmit = async (e) => {
        e.preventDefault();
        try {
            const response = await axios.post(
                `/products/from-bar-code/${barcode}`
            );
            setProducts([...userProducts, response.data.product]);
            alert("Товар додано за штрих-кодом!");
            setShowBarcodeModal(false);
            setBarcode("");
        } catch (error) {
            console.error("Error fetching product by barcode:", error);
            alert("Не вдалося знайти товар за штрих-кодом.");
        }
    };

    const handleChange = (e) => {
        const { name, value, files } = e.target;
        if (name === "image") {
            setFormData({ ...formData, image: files[0] });
        } else {
            setFormData({ ...formData, [name]: value });
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        const data = new FormData();
        data.append("name", formData.name);
        data.append("country", formData.country);
        data.append("due_date", formData.due_date);
        data.append("open_days", formData.open_days);
        data.append("image", formData.image);

        try {
            const response = await axios.post("/products", data, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });

            console.log("Product created:", response.data);
            setProducts([...userProducts, response.data.product]);
            alert("Товар успішно створено!");
            setShowModal(false);
        } catch (error) {
            console.error("Error creating product:", error);
            alert("Сталася помилка при створенні товару.");
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
                            <div className="holovna">Головна</div>
                            <a className="tovary" href="#tovary">
                                Ваші товари
                            </a>
                            <a className="aboutUs" href="#aboutUs">
                                Про нас
                            </a>

                            <a className="myChois" href={"/my-care"}>
                                Мій догляд
                            </a>
                            <a href="/profile" className="account">
                                Акаунт
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            {/* <main className="main"> */}
            <div
                className="headScreen"
                style={{ backgroundImage: `url(${background})` }}
            >
                <div className="mainPhrace">
                    <div className="firstPh">Скануй та слідкуй!</div>
                    <div className="inscription">
                        Допоможемо вам залишатися гарними та здоровими, дбаючи
                        про терміни придатності вашої косметики. За допомогою
                        SkinCare ви зможете легко відстежувати дати придатності
                        кожного засобу, отримувати сповіщення про наближення
                        строку та завжди мати під контролем свої косметичні
                        засоби.
                    </div>
                </div>
                <div className="mainButtons">
                    <button
                        className="firstBut"
                        onClick={() => setShowBarcodeModal(true)}
                    >
                        Сканувати
                    </button>
                    <button
                        className="firstBut"
                        onClick={() => setShowModal(true)}
                    >
                        Ввести вручну
                    </button>
                </div>
            </div>

            <div className="mainYourTovar">
                <div className="phraceTovary" id="tovary">
                    Ваші товари
                </div>
                <div className="tovaryCards">
                    {userProducts.map((el) => (
                        <div className="card">
                            <img
                                src={el.image}
                                alt="photo"
                                className="productCard"
                            />
                            <div className="cardDescr">
                                <div className="nameTovar">{el.name}</div>
                                <div className="countryMade">{el.country}</div>
                                <div className="timeEnd">
                                    Придатний до: {el.due_date}
                                </div>
                            </div>
                            <div className="cardDays">
                                <div className="howToTimeEndNum">
                                    {el.open_days}
                                </div>
                                <div className="endDays">днів залишилось</div>
                            </div>
                        </div>
                    ))}
                </div>
            </div>

            <div className="mainAboutUs" id="aboutUs">
                <div className="phraceAbout">Про нас</div>
                <div className="includeAbout">
                    <p>
                        SkinCare — більше, ніж додаток. Це ваш помічник у
                        догляді за красою, який дозволяє стежити за термінами
                        придатності косметики легко і зручно.
                    </p>
                    <p>
                        Ми — команда SkinCare — створили сервіс, щоб кожен
                        користувач міг вчасно використовувати свої засоби та
                        бути впевненим у їхній безпеці.
                    </p>
                    <p>
                        Приєднуйтесь до нашої спільноти і керуйте красою з
                        розумом.
                    </p>
                </div>
            </div>
            {/* </main> */}

            {showModal && (
                <>
                    <div
                        className="modal-overlay"
                        onClick={() => setShowModal(false)}
                    ></div>
                    <div className="modalCreate">
                        <form
                            className="createProductForm"
                            onSubmit={handleSubmit}
                        >
                            <h3>Новий товар</h3>
                            <input
                                type="text"
                                name="name"
                                placeholder="Назва товару"
                                value={formData.name}
                                onChange={handleChange}
                                required
                            />
                            <input
                                type="text"
                                name="country"
                                placeholder="Країна"
                                value={formData.country}
                                onChange={handleChange}
                                required
                            />
                            <input
                                type="date"
                                name="due_date"
                                value={formData.due_date}
                                onChange={handleChange}
                                required
                            />
                            <input
                                type="number"
                                name="open_days"
                                placeholder="Термін після відкриття (днів)"
                                value={formData.open_days}
                                onChange={handleChange}
                                required
                                min="1"
                            />
                            <input
                                type="file"
                                name="image"
                                accept="image/*"
                                onChange={handleChange}
                                required
                            />
                            <div className="formActions">
                                <button type="submit" className="saveBtn">
                                    Створити
                                </button>
                                <button
                                    type="button"
                                    className="cancelBtn"
                                    onClick={() => setShowModal(false)}
                                >
                                    Скасувати
                                </button>
                            </div>
                        </form>
                    </div>
                </>
            )}

            {showBarcodeModal && (
                <>
                    <div
                        className="modal-overlay"
                        onClick={() => setShowBarcodeModal(false)}
                    ></div>
                    <div className="modalCreate">
                        <form
                            className="createProductForm"
                            onSubmit={handleBarcodeSubmit}
                        >
                            <h3>Додати товар за штрих-кодом</h3>
                            <input
                                type="text"
                                name="barcode"
                                placeholder="Введіть штрих-код"
                                value={barcode}
                                onChange={(e) => setBarcode(e.target.value)}
                                required
                            />
                            <div className="formActions">
                                <button type="submit" className="saveBtn">
                                    Знайти
                                </button>
                                <button
                                    type="button"
                                    className="cancelBtn"
                                    onClick={() => setShowBarcodeModal(false)}
                                >
                                    Скасувати
                                </button>
                            </div>
                        </form>
                    </div>
                </>
            )}

            <footer className="footer">
                <div className="footerInclude">
                    <div className="footerLogo">
                        <i className="fa-solid fa-leaf leaf2"></i>
                        <div className="footerSKin">SkinCare</div>
                    </div>
                    <div className="footerHide">
                        <a href="#main" className="fMain">
                            Головна
                        </a>
                        <a href="#tovary" className="fTovary">
                            Товари
                        </a>
                        <a href="#aboutUs" className="fAbout">
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
