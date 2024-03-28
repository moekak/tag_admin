/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./public/js/domainShow.js":
/*!*********************************!*\
  !*** ./public/js/domainShow.js ***!
  \*********************************/
/***/ (() => {

eval("const copy_btn = document.querySelector(\".js_copy_btn\")\r\n\r\ncopy_btn.classList.add(\"menu_active\")\r\n\r\nconst directory_btn = document.querySelector(\".js_directory_btn\")\r\nconst directory_site = document.querySelector(\".js_directory_site\")\r\nconst copy_site = document.querySelector(\".js_copy_site\")\r\n\r\nif(directory_btn !== null){\r\n   directory_btn.addEventListener(\"click\", ()=>{\r\n\r\n    \r\n        copy_site.classList.add(\"hidden\")\r\n        directory_site.classList.remove(\"hidden\")\r\n\r\n    }) \r\n}\r\nif(copy_btn !== null){\r\n   copy_btn.addEventListener(\"click\", ()=>{\r\n        copy_site.classList.remove(\"hidden\")\r\n        directory_site.classList.add(\"hidden\")\r\n\r\n    }) \r\n}\n\n//# sourceURL=webpack://tag_admin/./public/js/domainShow.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./public/js/domainShow.js"]();
/******/ 	
/******/ })()
;