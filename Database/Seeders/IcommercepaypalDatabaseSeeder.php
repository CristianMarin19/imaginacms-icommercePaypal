<?php

namespace Modules\Icommercepaypal\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Icommerce\Entities\PaymentMethod;

class IcommercepaypalDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Model::unguard();

        $name = config('asgard.icommercepaypal.config.paymentName');
        $result = PaymentMethod::where('name',$name)->first();

        if(!$result){

            $options['init'] = "Modules\Icommercepaypal\Http\Controllers\Api\IcommercePaypalApiController";
            $options['mainimage'] = null;
            $options['clientId'] = "";
            $options['clientSecret'] = "";
            $options['mode'] = "sandbox";
            $options['currency'] = "USD";
            $options['minimunAmount'] = 0;
            
            $titleTrans = 'Paypal';
            $descriptionTrans = 'icommercepaypal::icommercepaypals.description';

            $params = array(
                'name' => $name,
                'status' => 1,
                'options' => $options
            );
            $paymentMethod = PaymentMethod::create($params);

            $this->addTranslation($paymentMethod,'en',$titleTrans,$descriptionTrans);
            $this->addTranslation($paymentMethod,'es',$titleTrans,$descriptionTrans);

        }else{

            $this->command->alert("This method has already been installed !!");
            
        }


    }

    /*
    * Add Translations
    * PD: New Alternative method due to problems with astronomic translatable
    **/
    public function addTranslation($paymentMethod,$locale,$title,$description){

      \DB::table('icommerce__payment_method_translations')->insert([
          'title' => trans($title,[],$locale),
          'description' => trans($description,[],$locale),
          'payment_method_id' => $paymentMethod->id,
          'locale' => $locale
      ]);

    }

}
