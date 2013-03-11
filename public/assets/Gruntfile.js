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
					'components/bootstrap/docs/assets/js/bootstrap.js',
					'components/datatables/media/js/jquery.dataTables.js',
					'components/select2/select2.js',
					
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
					'components/select2/select2.css',
					
					'css/bootstrap-editable.css',
					'css/nav-fix.css',
					'css/style.css'
				]
		      }
			}
		}
	});
	
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-uglify');

	// Default task.
	grunt.registerTask('default', ['uglify', 'cssmin']);

};
