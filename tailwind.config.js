// tailwind.config.js

module.exports = {
    content: [
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        // Pastikan Anda juga membersihkan path vendor jika diperlukan
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                'kopi-dark': '#6B3E1A',      
                'kopi-primary': '#A0522D',   
                'kopi-accent': '#C68642',    
                'kopi-cream': '#FBF6F0',     
                'kopi-light-bg': '#F0DFB0',  
                'kopi-green': '#519872', 
            },
            fontFamily: {
                serif: ['Georgia', 'Times New Roman', 'serif'], 
                sans: ['Arial', 'sans-serif'],
            },
        },
    },
    plugins: [],
}