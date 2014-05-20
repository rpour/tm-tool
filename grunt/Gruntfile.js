'use strict';

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
    g.loadNpmTasks('grunt-devtools');

    g.option( 'force', true );
    g.initConfig({
        /***********************************************************************
         * GLOBALS
         **********************************************************************/
        pkg                 : g.file.readJSON('package.json'),
        project_root_folder : "..",
        project_browser_url : "http://localhost/github/tm-tool/source/web/app_dev.php",
        project_source      : "<%= project_root_folder %>/source",
        project_dist        : "<%= project_root_folder %>/dist",

        project_sass_dir    : "<%= project_source %>/src/Tmt/CoreBundle/Resources/public/sass",
        project_image_dir   : "<%= project_source %>/src/Tmt/CoreBundle/Resources/public/img",
        project_css_dir     : "<%= project_source %>/src/Tmt/CoreBundle/Resources/public/css",

        symfony_console     : "<%= project_source %>/app/console",

        /***********************************************************************
         * DEFINE TASK'S
         **********************************************************************/
        clean       : g.file.readJSON('tasks/symfony/clean.json'),
        open        : g.file.readJSON('tasks/open.json'),
        copy        : g.file.readJSON('tasks/copy.json'),
        cssmin      : g.file.readJSON('tasks/cssmin.json'),
        /* WATCHOUT: Minifies images in the src folder before building the
           project. Prevent minifying in every build process. */
        imagemin    : g.file.readJSON('tasks/imagemin.json'),
        /* WATCHOUT: .jshintrc is needed in the Gruntfile.js folder */
        jshint      : g.file.readJSON('tasks/jshint.json'),
        autoprefixer: g.file.readJSON('tasks/autoprefixer.json'),
        compass     : g.file.readJSON('tasks/compass.json'),
        files_check : g.file.readJSON('tasks/files-check.json'),
        phplint     : g.file.readJSON('tasks/phplint.json'),
        phpmd       : g.file.readJSON('tasks/phpmd.json'),
        uglify      : g.file.readJSON('tasks/uglify.json'),
        watch       : g.file.readJSON('tasks/watch.json'),
        /* WATCHOUT: Customize the shell.json file
           TODO: Language and bundle name as a task parameter */
        shell       : g.file.readJSON('tasks/symfony/shell.json'),
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
};
