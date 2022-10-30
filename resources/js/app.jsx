import './bootstrap';
import '../css/app.css';

import React from 'react';
import {createRoot} from 'react-dom/client';
import {createInertiaApp} from '@inertiajs/inertia-react';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import Theme from "./Components/Theme";

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.jsx`, import.meta.glob('./Pages/**/*.jsx')),
    setup({el, App, props}) {
        const root = createRoot(el);

        root.render(
            <Theme>
                <App {...props} />
            </Theme>
        );
    },
});
