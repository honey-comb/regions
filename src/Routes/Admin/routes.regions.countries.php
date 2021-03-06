<?php
/**
 * @copyright 2018 interactivesolutions
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * Contact InteractiveSolutions:
 * E-mail: hello@interactivesolutions.lt
 * http://www.interactivesolutions.lt
 */

declare(strict_types = 1);

Route::domain(config('hc.admin_domain'))
    ->prefix(config('hc.admin_url'))
    ->namespace('Admin')
    ->middleware(['web', 'auth'])
    ->group(function() {

        Route::get('regions/countries', 'HCCountryController@index')
            ->name('admin.regions.countries.index')
            ->middleware('acl:honey_comb_regions_regions_countries_list');

        Route::prefix('api/regions/countries')->group(function() {

            Route::get('/', 'HCCountryController@getListPaginate')
                ->name('admin.api.regions.countries')
                ->middleware('acl:honey_comb_regions_regions_countries_list');

            Route::get('options', 'HCCountryController@getOptions')
                ->name('admin.api.regions.countries.options');

            Route::prefix('{id}')->group(function() {

                Route::get('/', 'HCCountryController@getById')
                    ->name('admin.api.regions.countries.single')
                    ->middleware('acl:honey_comb_regions_regions_countries_list');

                Route::put('/', 'HCCountryController@update')
                    ->name('admin.api.regions.countries.update')
                    ->middleware('acl:honey_comb_regions_regions_countries_update');

                Route::patch('/', 'HCCountryController@patch')
                    ->name('admin.api.regions.countries.patch')
                    ->middleware('acl:honey_comb_regions_regions_countries_update');
            });
        });
    });
