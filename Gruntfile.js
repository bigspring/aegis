
module.exports = function(grunt) {

	grunt.initConfig({

		pkg: grunt.file.readJSON('package.json'),
		banner: '/*! <%= pkg.title || pkg.name %> - v<%= pkg.version %> - ' + // name/version
			'<%= grunt.template.today("yyyy-mm-dd") %>\n' + // current year
			'<%= pkg.homepage ? "* " + pkg.homepage + "\\n" : "" %>' + // homepage (not currently set in package.json)
			'* Copyright (c) <%= grunt.template.today("yyyy") %> <%= pkg.author.name %>;' +
			'* License: <%= pkg.license %>\n\n',
//			'* Packages: <%= _.map(pkg.devDependencies, function(package, key) {return key}).join(", ") %>\n' +

		dirs: {
			js_src: 'assets/js/',
			less_src: 'assets/less/',
			css_src: 'assets/css/',
			dist_src: 'assets/dist/'
		},

		deps: {
			js: [
				'<% dirs.js_src %>/site.js'
			],
			less: [
				'<% dirs.less_src %>/base.less'
			],
			css: [
				'<% dirs.css_src %>/base.css'
			]
		},

		// JS Tasks
		concat: {
			options: {
				banner: '<%= banner %>',
				stripBanners: true
		  	},
			main: {
				src: '<% deps.js %>',
				dest: 'assets/dist/<%= pkg.name %>.js'
			}
		},
		uglify: {
			options: {
				banner: '<%= banner %>'
			},
			dist: {
				src: '<%= concat.dist.dest %>',
				dest: 'assets/dist/base.min.js'
			}
		},

		// CSS Tasks
		less: {
			options: {
				banner: '<%= banner %>'
			},
			main: {
				files: {
					'css/base.css': '<% deps.less %>'
				}
			}
		},
		cssmin: {
			options: {
				banner: '<%= banner %>'
			},
			minify: {
				expand: true,
				cwd: '<%= dirs.css_src %>',
				src: ['*.css', '!*.min.css'], // currently not minimising all files in css/
				dest: '<%= dirs.css_src %>',
				ext: '.min.css'
			}
		},

		watch: {
			js: {
				files: ['assets/dist/*.js'],
				tasks: ['concat', 'uglify']
			},
			css: {
				files: ['assets/dist/*.css'],
				tasks: ['less', 'cssmin']
			}
		}
	});

	require('load-grunt-tasks')(grunt);

	// Default task.
	grunt.registerTask('default', ['concat', 'uglify', 'watch']);
};
