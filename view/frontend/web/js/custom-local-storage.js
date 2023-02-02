define([

    'jquery',

    'mage/storage',

    'jquery/jquery-storageapi'

], function ($) {

    'use strict';



    var cacheKey = 'related-product-data', // define field cache key of storage

        // define local storage namespace
        storage = $.initNamespaceStorage('related-product-local-storage').localStorage,




        /**

         * @param {Object} data

         */

        saveData = function (data, ttl = 0) {
            const now = new Date();

            data.expiry = now.getTime() + ttl;

            storage.set(cacheKey, data);

        },



        /**

         * @return {*}

         */

        getData = function () {

            if (!storage.get(cacheKey)) {

                reset();

            }

            return storage.get(cacheKey);

        },



        reset = function () {

            var data = {

                hasData: false, // all data have been defined below

                relatedProductData: null,

            };

            saveData(data);

        };

    return {

// use reset function to reset all data saved in storage

        reset : function () {

            reset();

        },



// define getter, setter of data to save in local storage

        getHasData: function () {

            var obj = getData();

            return obj.hasData;

        },



        setHasData: function (hasData) {

            var obj = getData();

            obj.hasData = hasData;

            saveData(obj);

        },



        getRelatedProductData: function () {
            console.log(333555)
            var obj = getData();
            const now = new Date()

            // compare the expiry time of the item with the current time
            console.log(1,now.getTime());
            console.log(2,obj.expiry);
            if (now.getTime() > obj.expiry) {
                // If the item is expired, delete the item from storage
                // and return null
                reset();
                return null
            }

            return obj.relatedProductData;

        },



        setRelatedProductData: function (relatedProductData, ttl) {

            var obj = getData();

            obj.relatedProductData = relatedProductData;

            saveData(obj, ttl);

        }

    };

});