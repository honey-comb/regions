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

Route::prefix(config('hc.admin_url'))
    ->namespace('Admin')
    ->middleware(['web', 'auth'])
    ->group(function() {

        Route::get('regions/city', 'HCCityController@index')
            ->name('admin.regions.city.index')
            ->middleware('acl:honey_comb_regions_regions_city_admin_list');

        Route::prefix('api/regions/city')->group(function() {

            Route::get('/', 'HCCityController@getListPaginate')
                ->name('admin.api.regions.city')
                ->middleware('acl:honey_comb_regions_regions_city_admin_list');

            Route::get('options', 'HCCityController@getOptions')
                ->name('admin.api.regions.city.options');

            Route::post('/', 'HCCityController@store')
                ->name('admin.api.regions.city.create')
                ->middleware('acl:honey_comb_regions_regions_city_admin_create');

            Route::delete('/', 'HCCityController@deleteSoft')
                ->name('admin.api.regions.city.delete')
                ->middleware('acl:honey_comb_regions_regions_city_admin_delete');

            Route::delete('force', 'HCCityController@deleteForce')
                ->name('admin.api.regions.city.delete.force')
                ->middleware('acl:honey_comb_regions_regions_city_admin_delete_force');

            Route::post('restore', 'HCCityController@restore')
                ->name('admin.api.regions.city.restore')
                ->middleware('acl:honey_comb_regions_regions_city_admin_restore');

            Route::prefix('{id}')->group(function() {

                Route::get('/', 'HCCityController@getById')
                    ->name('admin.api.regions.city.single')
                    ->middleware('acl:honey_comb_regions_regions_city_admin_list');

                Route::put('/', 'HCCityController@update')
                    ->name('admin.api.regions.city.update')
                    ->middleware('acl:honey_comb_regions_regions_city_admin_update');

                Route::patch('/', 'HCCityController@patch')
                    ->name('admin.api.regions.city.patch')
                    ->middleware('acl:honey_comb_regions_regions_city_admin_update');

            });
        });
    });
