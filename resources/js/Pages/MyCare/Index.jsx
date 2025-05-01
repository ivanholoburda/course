import React, { useState } from "react";
import { router } from "@inertiajs/react";
import "./Index.css";
import axios from "axios";

export default function Index({ products }) {
    const [userProducts, setProducts] = useState(products.data);
    const [searchName, setSearchName] = useState("");
    const [searchDays, setSearchDays] = useState("");
    const [sortOrder, setSortOrder] = useState(null);
    const [showEditModal, setShowEditModal] = useState(false);
    const [editProduct, setEditProduct] = useState(null);
    const [editForm, setEditForm] = useState({
        name: "",
        country: "",
        due_date: "",
        open_days: "",
        image: null,
    });

    const openEditModal = (product) => {
        setEditProduct(product);
        setEditForm({
            name: product.name || "",
            country: product.country || "",
            due_date: product.due_date || "",
            open_days: product.open_days || "",
            image: null,
        });
        setShowEditModal(true);
    };

    const handleEditChange = (e) => {
        const { name, value, files } = e.target;
        if (name === "image") {
            setEditForm({ ...editForm, image: files[0] });
        } else {
            setEditForm({ ...editForm, [name]: value });
        }
    };

    const handleEditSubmit = async (e) => {
        e.preventDefault();

        const data = new FormData();
        if (editForm.name) data.append("name", editForm.name);
        if (editForm.country) data.append("country", editForm.country);
        if (editForm.due_date) data.append("due_date", editForm.due_date);
        if (editForm.open_days) data.append("open_days", editForm.open_days);
        if (editForm.image) data.append("image", editForm.image);

        try {
            const response = await axios.post(
                `/products/update/${editProduct.id}`,
                data,
                {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                }
            );

            console.log("Товар оновлено:", response.data);
            alert("Товар успішно оновлено!");
            setShowEditModal(false);
            setProducts(
                userProducts.map((prod) =>
                    prod.id === response.data.product.id
                        ? response.data.product
                        : prod
                )
            );
        } catch (error) {
            console.error("Помилка при оновленні товару:", error);
            alert("Помилка при оновленні товару.");
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();

        const query = {};
        if (searchName) query.name = searchName;
        if (searchDays) query.open_days = searchDays;
        if (sortOrder) query.direction = sortOrder;

        router.get("/my-care", query);
    };

    const handleDelete = (productId) => {
        if (confirm("Ви дійсно хочете видалити цей товар?")) {
            router.delete(`/products/${productId}`, {
                onSuccess: () => {
                    alert("Товар успішно видалено!");
                },
                onError: () => {
                    alert("Помилка при видаленні товару.");
                },
            });
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

                            <a className="myChoisMyCare" href={"/my-care"}>
                                Мій догляд
                            </a>
                            <a className="account" href="/profile">
                                Акаунт
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <form className="filters" onSubmit={handleSubmit}>
                <input
                    type="text"
                    placeholder="Пошук за назвою"
                    value={searchName}
                    onChange={(e) => setSearchName(e.target.value)}
                />

                <input
                    type="number"
                    placeholder="Пошук по днях"
                    value={searchDays}
                    onChange={(e) => setSearchDays(e.target.value)}
                />

                <select
                    value={sortOrder || ""}
                    onChange={(e) => setSortOrder(e.target.value || null)}
                >
                    <option value="">Сортування</option>
                    <option value="asc">Сортувати ↑ (за зростанням)</option>
                    <option value="desc">Сортувати ↓ (за спаданням)</option>
                </select>

                <button type="submit" className="applyBtn">
                    Застосувати фільтри
                </button>

                <button
                    type="button"
                    className="resetBtn"
                    onClick={() => {
                        setSearchName("");
                        setSearchDays("");
                        setSortOrder(null);
                        router.get("/my-care");
                    }}
                >
                    Відмінити фільтрацію
                </button>
            </form>

            <div className="tovaryCards">
                {userProducts.length > 0 ? (
                    userProducts.map((el) => (
                        <div className="card" key={el.id}>
                            <img
                                src={el.image}
                                alt="photo"
                                className="productCard"
                            />
                            <div className="cardDescr">
                                <div className="nameTovar">
                                    {el.name} {el.id}
                                </div>
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
                            <div className="cardActions">
                                <button
                                    className="editBtn"
                                    onClick={() => openEditModal(el)}
                                >
                                    ✏️ Редагувати
                                </button>
                                <button
                                    className="deleteBtn"
                                    onClick={() => handleDelete(el.id)}
                                >
                                    🗑️ Видалити
                                </button>
                            </div>
                        </div>
                    ))
                ) : (
                    <h1>У вас немає доданих товарів!</h1>
                )}
            </div>

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
            {showEditModal && (
                <>
                    <div
                        className="modal-overlay"
                        onClick={() => setShowEditModal(false)}
                    ></div>
                    <div className="modalCreate">
                        <form
                            className="createProductForm"
                            onSubmit={handleEditSubmit}
                        >
                            <h3>Редагувати товар</h3>
                            <input
                                type="text"
                                name="name"
                                placeholder="Назва товару"
                                value={editForm.name}
                                onChange={handleEditChange}
                            />
                            <input
                                type="text"
                                name="country"
                                placeholder="Країна"
                                value={editForm.country}
                                onChange={handleEditChange}
                            />
                            <input
                                type="date"
                                name="due_date"
                                value={editForm.due_date}
                                onChange={handleEditChange}
                            />
                            <input
                                type="number"
                                name="open_days"
                                placeholder="Термін після відкриття (днів)"
                                value={editForm.open_days}
                                onChange={handleEditChange}
                                min="1"
                            />
                            <input
                                type="file"
                                name="image"
                                accept="image/*"
                                onChange={handleEditChange}
                            />
                            <div className="formActions">
                                <button type="submit" className="saveBtn">
                                    Оновити
                                </button>
                                <button
                                    type="button"
                                    className="cancelBtn"
                                    onClick={() => setShowEditModal(false)}
                                >
                                    Скасувати
                                </button>
                            </div>
                        </form>
                    </div>
                </>
            )}
        </>
    );
}
