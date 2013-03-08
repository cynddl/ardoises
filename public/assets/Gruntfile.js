module.exports = function(grunt) {

	// Project configuration.
	grunt.initConfig({
		
		pkg: grunt.file.readJSON('package.json'),


		uglify: {
		    options: {
		      mangle: {
		        except: []
		      }
		    },
		    dist: {
		      files: {
		        'js/main.min.js': [
					'components/jquery/jquery.js',
					'js/jquery.dataTables.min.js',
					'components/bootstrap/docs/assets/js/bootstrap.js',
					'js/bootstrap-editable.js',
					'js/DT_bootstrap.js',
					'js/patternizer.min.js',
					'js/script.js'
				]
		    }
		  }
		},

		
		cssmin: {
			compress: {
		      files: {
		        "css/main.min.css": [
					'components/bootstrap/docs/assets/css/bootstrap.css',
					'components/bootstrap/docs/assets/css/bootstrap-responsive.css',
					'css/bootstrap-editable.css',
					'css/nav-fix.css',
					'css/style.css'
				]
		      }
			}
		}
	});
	
	grunt.loadNpmTasks('grunt-css');
	grunt.loadNpmTasks('grunt-contrib-cssmin');

	// Default task.
	grunt.registerTask('default', ['uglify', 'cssmin']);

};
