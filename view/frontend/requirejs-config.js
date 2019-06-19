/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    paths: {
        'credovaPlugin': ['//plugin.credova.com/plugin.min'],
        polyfill: ['//unpkg.com/@babel/polyfill@7.4.4/dist/polyfill.min'],
        es6: 'ClassyLlama_Credova/js/es6',
        'plugin-proposal-object-rest-spread': 'unpkg.com/@babel/plugin-proposal-object-rest-spread',
        babel: '//unpkg.com/@babel/standalone/babel.min',
        'babel-plugin-module-resolver': 'ClassyLlama_Credova/js/babel-plugin-module-resolver-standalone'
    },
    config: {
        es6: {
            resolveModuleSource: function (sourcePath) {
                return sourcePath;
            },
            extraPlugins: [
                'proposal-object-rest-spread'
            ],
            presets: [
                'es2015'
            ]
        }
    },
    shim: {
        'es6': {
            deps: ['polyfill']
        },
        'babel': {
            deps: ['polyfill']
        },
        'underscore': {
            exports: 'CRDV'
        }
    }
};
