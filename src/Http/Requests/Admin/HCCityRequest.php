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

namespace HoneyComb\Regions\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class HCCityRequest
 * @package HoneyComb\Regions\Http\Requests\Admin
 */
class HCCityRequest extends FormRequest
{
    /**
     * @var array
     */
    private $patchKeys = ['visible'];

    /**
     * Get request inputs
     *
     * @return array
     */
    public function getRecordData(): array
    {
        return $this->all();
    }

    /**
     * Get ids to delete, force delete or restore
     *
     * @return array
     */
    public function getListIds(): array
    {
        return $this->input('list', []);
    }

    /**
     * Getting translations
     *
     * @return array
     */
    public function getTranslations(): array
    {
        return $this->input('translations', []);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get only available to update fields
     *
     * @return array
     */
    public function getPatchValues(): array
    {
        return $this->only($this->patchKeys);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        switch ($this->method()) {
            case 'POST':
                if ($this->segment(4) == 'restore') {
                    return [
                        'list' => 'required|array',
                    ];
                }

                return [];

            case 'PUT':

                return [];

            case 'PATCH':
                return [
                    'visible' => 'required',
                ];

            case 'DELETE':
                return [
                    'list' => 'required|array',
                ];
        }

        return [];
    }

    /**
     * @return bool
     */
    public function isResponseForOptions(): bool
    {
        if ($this->has('hc_new')) {
            return true;
        }

        return false;
    }
}
