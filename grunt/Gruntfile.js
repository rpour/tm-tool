//
// This is a Gruntfile.js template for Symfony2 projects included
// compass as a frontend *framework*.
//
'use strict';

//
// MANUAL: Replace all '{REPLACE}' placeholder
//
module.exports = function (g) {
    g.loadNpmTasks('grunt-contrib-clean');
    g.loadNpmTasks('grunt-contrib-copy');
    g.loadNpmTasks('grunt-contrib-cssmin');
    g.loadNpmTasks('grunt-contrib-jshint');
    g.loadNpmTasks('grunt-contrib-compass');
    g.loadNpmTasks('grunt-contrib-uglify');
    g.loadNpmTasks('grunt-contrib-watch');
    g.loadNpmTasks('grunt-contrib-imagemin');
    g.loadNpmTasks('grunt-phpmd');
    g.loadNpmTasks('grunt-open');
    g.loadNpmTasks('grunt-files-check');
    g.loadNpmTasks('grunt-autoprefixer');
    g.loadNpmTasks('grunt-phplint');
    g.loadNpmTasks('grunt-shell');


    g.option( 'force', true );
    g.initConfig({
        // ------------------------------------------------------------- Globals
        pkg:                 g.file.readJSON('package.json'),
        project_root_folder: "..",
        project_browser_url: "http://localhost/github/tm-tool/source/web/app_dev.php",
        project_source:      "<%= project_root_folder %>/source",
        project_dist:        "<%= project_root_folder %>/dist",

        // ---------------------------------------------------- Symfony2 globals
        symfony_console: "<%= project_source %>/app/console",



        // ---------------------------------------------------------- Task Files
        // ---------------------------------------------------------------------
        // ------------------------------------------------------------### Clean
        clean_cons: {
            "source": "<%= project_source %>",
            "dist":   "<%= project_dist %>"
        },
        clean: g.file.readJSON('tasks/symfony/clean.json'),
        // -----------------------------------------------------### Open Browser
        open: g.file.readJSON('tasks/open.json'),
        // -------------------------------------------------------------### Copy
        copy_cons: {
            "root": "<%= project_source %>/",
            "dest": "<%= project_dist %>/"
        },
        copy: g.file.readJSON('tasks/copy.json'),
        // ----------------------------------------------------------### CSS Min
        cssmin_cons: {
            "cwd":  "<%= project_dist %>/web",
            "dest": "<%= project_dist %>/web",
        },
        cssmin: g.file.readJSON('tasks/cssmin.json'),
        // --------------------------------------------------------### Image Min
        //
        // WATCHOUT: Minifies images in the src folder before building the
        //           project. Prevent minifying in every build process.
        //
        imagemin_cons: {
            "cwd":  "<%= project_source %>/src",
            "dest": "<%= project_source %>/src",
        },
        imagemin: g.file.readJSON('tasks/imagemin.json'),
        // -----------------------------------------------------------### JSHint
        //
        // WATCHOUT: .jshintrc is needed in the Gruntfile.js folder
        //
        jshint_cons: {
            "targets": ["<%= project_source %>/src/**/*.js"],
            "ignores": ["<%= project_source %>/src/**/vendors/*.js"],
        },
        jshint: g.file.readJSON('tasks/jshint.json'),
        // -----------------------------------------------------### Autoprefixer
        autoprefixer_cons: {
            "browsers": ["last 2 version", "ie 9"],
            "src":      "<%= project_source %>/src/WhereGroup/CoreBundle/Resources/public/styles/*.css",
            "dest":     "<%= project_source %>/src/WhereGroup/CoreBundle/Resources/public/styles/",
        },
        autoprefixer: g.file.readJSON('tasks/autoprefixer.json'),
        // ----------------------------------------------------------### Compass
        compass_cons: {
            "import_path": "<%= project_source %>/src/WhereGroup/CoreBundle/Resources/public/sass",
            "location":    "<%= project_source %>/src/WhereGroup/CoreBundle/Resources/public"
        },
        compass: g.file.readJSON('tasks/compass.json'),
        // ------------------------------------------------------### Files Check
        filescheck_cons: {
            "script_pattern": "css\\(|show\\(|hide\\(",
            "script_src":     ["<%= project_source %>/src/**/scripts/*.js"],
            "html_pattern":   "style=|<style",
            "html_src":       ["<%= project_source %>/src/**/*.html.twig"],
        },
        files_check: g.file.readJSON('tasks/files-check.json'),
        // ----------------------------------------------------------### Phplint
        phplint_cons: {
            "swap":  "<%= project_source %>/app/cache",
            "files": ["<%= project_source %>/app/**/*.php",
                      "<%= project_source %>/src/**/*.php"],
        },
        phplint: g.file.readJSON('tasks/phplint.json'),
        // ------------------------------------------------------------### Phpmd
        phpmd_cons: {
            "dir":     "<%= project_source %>/src",
            "exclude": "Entity,Form",
        },
        phpmd: g.file.readJSON('tasks/phpmd.json'),
        // --------------------------------------------------------### Uglify Js
        uglify_cons: {
            "src":  "<%= project_dist %>/web/",
            "dest": "<%= project_dist %>/web/",
        },
        uglify: g.file.readJSON('tasks/uglify.json'),
        // ------------------------------------------------------------### Watch
        watch_cons: {
            "reload_port": 1337,
            "src":         "<%= project_source %>/src/",
        },
        watch: g.file.readJSON('tasks/watch.json'),
        // ------------------------------------------------------------### Shell
        //
        // WATCHOUT: Customize the shell.json file
        //     TODO: Language and bundle name as a task parameter
        //
        shell_cons: {
            "path":         "<%= symfony_console %>",
            "trans_lang":   "de",
            "trans_bundle": "{REPLACE}"
        },
        shell: g.file.readJSON('tasks/symfony/shell.json'),
    });


    /***************************************************************************
     * REGISTER TASK'S
     **************************************************************************/
    g.registerTask('dev', [
        'clean:cache',
        'open',
        'compass',
        'autoprefixer',
        'shell:symlinks',
        'watch'
   ]);

    g.registerTask('build', [
        'shell:symlinks',
        'shell:assetic',
        'clean:dist',
        'copy:build',
        'clean:build',
        'imagemin',
        'cssmin',
        'uglify'
    ]);

    g.registerTask('symlinks' ['shell:symlinks']);
    g.registerTask('assets',  ['shell:assetic']);
    g.registerTask('cache',   ['clean:cache']);
    g.registerTask('trans',   ['shell:trans']);
    g.registerTask('md',      ['phpmd']);

    // g.registerTask('front-test', ['browserSync:test']);
};
