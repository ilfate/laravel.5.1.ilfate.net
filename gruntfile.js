(function () {
    'use strict';
}());
module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        concat: {
            options: {
                separator: ' \r\n '
            },
            dist: {
                src: [
                    'resources/assets/js/vendor/jquery-1.8.2.min.js',
                    'resources/assets/js/vendor/jquery-additional.js',
                    'resources/assets/js/vendor/preloadjs-0.2.0.min.js',
                    'resources/assets/js/vendor/easeljs-0.5.0.min.js',
                    'resources/assets/js/vendor/imagesloaded.pkgd.min.js',
                    'resources/assets/js/vendor/mustache.js',
                    'resources/assets/js/vendor/bootstrap/bootstrap.min.js',
                    'resources/assets/js/vendor/dropzone.js',
                    'resources/assets/js/ajax.js',
                    'resources/assets/js/events.js',
                    'resources/assets/js/form.js',
                    'resources/assets/js/index.js',
                    'resources/assets/js/guess/main.js',
                    'resources/assets/js/canvasActions.js',
                    'resources/assets/js/math-effect/math-effect-page.js',
                    'resources/assets/js/math-effect/td.game.js',
                    'resources/assets/js/math-effect/td.facet.js',
                    'resources/assets/js/math-effect/td.map.js',
                    'resources/assets/js/math-effect/td.map.config.js',
                    'resources/assets/js/math-effect/td.unit.js',
                    'resources/assets/js/robot-rock/PulsarCV.js',
                    'resources/assets/js/robot-rock/PlLayer.js',
                    'resources/assets/js/robot-rock/PlDrawing.js',
                    'resources/assets/js/robot-rock/PlObject.js',
                    'resources/assets/js/robot-rock/PlImage.js',
                    'resources/assets/js/robot-rock/PlTable.js',
                    'resources/assets/js/robot-rock/RRgame.js',
                    'resources/assets/js/robot-rock/botLoader.js'
                ],
                dest: 'public/js/main-grunt.js'
            }
        },
        uglify: {
            options: {
                banner: '/*! <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n '
            },
            dist: {
                files: {
                    'public/js/main.min.js': ['<%= concat.dist.dest %>']
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.registerTask('default', ['concat', 'uglify']);
};

