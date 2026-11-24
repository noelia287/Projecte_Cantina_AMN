// Importa la funció defineConfig de Vite.
// Permet definir la configuració del projecte amb autocompletat
// i validació de les opcions.
import { defineConfig } from 'vite';

// Exporta la configuració de Vite.
// Vite utilitzarà aquest objecte quan iniciï el servidor
// de desenvolupament o executi la compilació.
export default defineConfig({

  // Configuració del servidor de desenvolupament.
  server: {

    // Habilita CORS (Cross-Origin Resource Sharing).
    // Permet que una pàgina servida per Apache/PHP pugui
    // carregar recursos del servidor de desenvolupament Vite.
    cors: true,

    // Origen del servidor de Vite.
    // Fa que els recursos del CSS (com les fonts de les icones)
    // es demanin a Vite i no a Apache.
    origin: 'http://localhost:5173',
  },

  // Configuració de la compilació per a producció.
  build: {

    // Genera un fitxer manifest.json.
    // Conté informació sobre els fitxers CSS i JS generats
    // i permet que PHP els identifiqui automàticament.
    manifest: true,

    // Indica la carpeta on es guardaran els fitxers compilats.
    // En aquest cas, els recursos es generaran dins de dist/.
    outDir: 'dist',

    // Esborra el contingut anterior de dist/ abans de compilar.
    // Evita que quedin fitxers obsolets de compilacions anteriors.
    emptyOutDir: true,

    // Configuració del procés de compilació de Rollup.
    // Rollup és l'eina que Vite utilitza per generar els fitxers finals.
    rollupOptions: {

      // Defineix el fitxer JavaScript principal del projecte.
      // A partir d'aquest fitxer, Vite detectarà els mòduls JS
      // importats i el Sass associat per generar els recursos.
      input: 'src/js/main.js',
    },
  },
});
