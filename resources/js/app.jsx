import {createInertiaApp} from '@inertiajs/react'
import {createRoot} from 'react-dom/client'
import React from 'react';
import '../css/app.css'
//test
createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true })
        return pages[`./Pages/${name}.jsx`];
    },
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />)
    },
})
