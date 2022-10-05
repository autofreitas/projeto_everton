var gulp 			= require('gulp');
var concat 			= require('gulp-concat');
var uglify 			= require('gulp-uglify');
var cssmin  		= require('gulp-cssmin');
var sourcemaps 		= require('gulp-sourcemaps');
var notify 			= require('gulp-notify');
var del 			= require('del');
var autoprefixer 	= require('gulp-autoprefixer');
var ngAnnotate 		= require('gulp-ng-annotate');
var stripDebug 		= require('gulp-strip-debug');

/*********************
 * JS PATH
 * */
var js_default = [
	'shared/scripts/cemf-utility.js',
];

var js_appv1 = [
	'themes/appv1/assets/js/scripts-web.js',
];



/*********************
 * CSS ATH
 * */
var css_default 	= ['shared/styles/material.cemf.default.css'];
var css_appv1		= ['themes/appv1/assets/css/material.cemf.appv1.css'];




/***********************
 *  TASKS  DEFAULT
 */

// Javascript Tasks
gulp.task('js', function() {
	var mod = null;
	var javascript = "";
	if(process.argv.indexOf('--appv1') !== -1) {
		mod = "appv1";
		javascript = js_appv1;
	}

	var build = "themes/"+mod+"/assets/build/js";

	if(process.argv.indexOf('--default') !== -1){
		mod = "default";
		build = "shared/build/js";
		javascript = js_default;
	}



	if(mod == null){
		console.log("Precisa Atribuir os parametros:  --appv1 --default");
		return;
	}

	var timestamp = Math.round(new Date()/1000);
	var filename = 'scripts-'+mod+'-'+timestamp +'.min.js';

	del([build+'/scripts*']);

	return gulp
		.src(javascript)
		.pipe(ngAnnotate())
		.pipe(concat(filename))
		.pipe(sourcemaps.init())
		.pipe(stripDebug())
		.pipe(uglify())
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(build))
		.pipe(notify({message: "Javascript "+mod+"- tasks completed " + filename}))
});

// Main tasks
gulp.task('css', function() {

	var mod = null;
	var css = "";
	if(process.argv.indexOf('--appv1') !== -1) {
		mod = "appv1";
		css = css_appv1;
	}

	var build = "themes/"+mod+"/assets/build/css";

	if(process.argv.indexOf('--default') !== -1){
		mod = "default";
		build = "shared/build/css";
		css = css_default;
	}


	// console.log(mod);
	// console.log(build);
	// console.log(javascript);
	//return;

	if(mod == null){
		console.log("Precisa Atribuir os parametros:  --appv1 --default");
		return;
	}

	var timestamp = Math.round(new Date()/1000);
	var filename = 'styles-'+mod+'-'+timestamp +'.min.css';

	del([build+'/styles*']);
	return gulp
		.src(css)
		.pipe(autoprefixer({
			browsers: ['last 2 versions'],
			cascade: false
		}))
		.pipe(concat(filename))
		.pipe(sourcemaps.init())
		.pipe(cssmin())
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(build))
		.pipe(notify({message: "CSS "+mod+"- tasks completed " + filename}));
	
});

