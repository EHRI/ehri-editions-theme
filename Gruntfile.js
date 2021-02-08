
/**
 * @package     omeka
 * @subpackage  ehri-editions-theme
 * @copyright   2021 King's College London Department of Digital Humanities
 */

module.exports = function(grunt) {

  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-compress');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-watch');

  var pkg = grunt.file.readJSON('package.json');
  const sass = require('node-sass');

  grunt.initConfig({

    clean: {
      pkg: 'pkg'
    },

    sass: {
      options: {
        implementation: sass,
        sourceMap: true
      },
      dist: {
        files: {
          'css/theme.css': 'scss/theme.scss',
          'css/print.css': 'scss/print.scss'
        }
      }
    },

    watch: {
      payload: {
        files: [
          'scss/**',
          'javascripts/editions.js'
        ],
        tasks: 'build'
      }
    },

    uglify: {
      options: {
        mangle: false
      },
      my_target: {
        files: {
          'javascripts/theme.min.js': ['javascripts/editions.js']
        }
      }
    },

    compress: {

      dist: {
        options: {
          archive: 'pkg/ehri-editions-theme-'+pkg.version+'.zip'
        },
        dest: 'ehri/',
        src: [

          '**',

          // GIT
          '!.git/**',

          // NPM
          '!package.json',
          '!node_modules/**',

          // GRUNT
          '!.grunt/**',
          '!Gruntfile.js',

          // DIST
          '!pkg/**',

          // Editor settings
          '!*.vim',
          '!.idea',
          '!*.iml',
        ]
      }

    }

  });

  // Build the application.
  grunt.registerTask('build', [
    'clean',
    'sass',
    'uglify',
  ]);

  // Spawn release package.
  grunt.registerTask('package', [
    'build',
    'compress'
  ]);

  grunt.registerTask('default', [
      'watch'
  ]);
};
