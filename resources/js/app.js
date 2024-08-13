import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createI18n } from 'vue-i18n';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import helpers from './helpers';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

async function loadLocaleMessages() {
    var messages = {}
    const resources = import.meta.glob('/resources/lang/*.json', { query: 'raw' })

    for (const path in resources) {
      const raw = await resources[path]()
      const lang = path.match(/([^/]+)\.json$/)[1]
      var langMsg = JSON.parse(raw)

      Object.entries(langMsg).forEach(([key, value]) => {
        langMsg[key] = String(value).replace(/:(\w+)/g, '{$1}')
      })
      messages[lang] = langMsg
    }
    return messages
}


createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    async setup({ el, App, props, plugin }) {
        const i18n = createI18n({
            legacy: false,
            globalInjection: true,
            locale: __locale,
            fallbackLocale: __fallback_locale,
            messages: await loadLocaleMessages(),
        })
        
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(i18n)
            .mixin(helpers)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
