// Importa la funciÃ³ defineConfig de Vite.
// Permet definir la configuraciÃ³ del projecte amb autocompletat
// i validaciÃ³ de les opcions.
import { defineConfig } from 'vite';

// Exporta la configuraciÃ³ de Vite.
// Vite utilitzarÃ  aquest objecte quan iniciÃ¯ el servidor
// de desenvolupament o executi la compilaciÃ³.
export default defineConfig({

  // ConfiguraciÃ³ del servidor de desenvolupament.
  server: {

    // Habilita CORS (Cross-Origin Resource Sharing).
    // Permet que una pÃ gina servida per Apache/PHP pugui
    // carregar recursos del servidor de desenvolupament Vite.
    cors: true,
  },

  // ConfiguraciÃ³ de la compilaciÃ³ per a producciÃ³.
  build: {

    // Genera un fitxer manifest.json.
    // ContÃ© informaciÃ³ sobre els fitxers CSS i JS generats
    // i permet que PHP els identifiqui automÃ ticament.
    manifest: true,

    // Indica la carpeta on es guardaran els fitxers compilats.
    // En aquest cas, els recursos es generaran dins de dist/.
    outDir: 'dist',

    // Esborra el contingut anterior de dist/ abans de compilar.
    // Evita que quedin fitxers obsolets de compilacions anteriors.
    emptyOutDir: true,

    // ConfiguraciÃ³ del procÃ©s de compilaciÃ³ de Rollup.
    // Rollup Ã©s l'eina que Vite utilitza per generar els fitxers finals.
    rollupOptions: {

      // Defineix el fitxer JavaScript principal del projecte.
      // A partir d'aquest fitxer, Vite detectarÃ  els mÃ²duls JS
      // importats i el Sass associat per generar els recursos.
      input: 'src/js/main.js',
    },
  },
});
