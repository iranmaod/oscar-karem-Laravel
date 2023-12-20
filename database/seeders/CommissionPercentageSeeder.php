<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommissionPercentage;

class CommissionPercentageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      CommissionPercentage::insert(
        array(
          array('id' => '1','name' => 'Setter Freelancer - Normal Setting & Closing Process','slug' => 'setter-freelancer-normal-setting-and-closing-process','commission_employee_type' => 'setter','commission_lead' => 'paid_leads','commission_payment_type' => 'commission','first_lead' => '5.00','second_lead' => '5.00','third_lead' => '5.00','fourth_lead' => '5.00','fifth_lead' => '5.00','onward_lead' => '5.00','hs_deal_name' => 'AUFTRAG GEWONNEN - MIT SETTER','status_id' => '3','created_at' => '2021-12-26 14:19:33','updated_at' => '2021-12-26 14:19:33'),
          array('id' => '2','name' => 'Setter - Freelancer - Person makes the deal alone','slug' => 'setter-freelancer-person-makes-the-deal-alone','commission_employee_type' => 'setter','commission_lead' => 'paid_leads_individual','commission_payment_type' => 'commission','first_lead' => '15.00','second_lead' => '15.00','third_lead' => '15.00','fourth_lead' => '15.00','fifth_lead' => '15.00','onward_lead' => '15.00','hs_deal_name' => 'AUFTRAG GEWONNEN - OHNE SETTER','status_id' => '3','created_at' => '2021-12-26 14:39:05','updated_at' => '2021-12-26 14:39:05'),
          array('id' => '3','name' => 'Setter Salary + Commission - Normal Setting & Closing Process','slug' => 'setter-salary-commission-normal-setting-and-closing-process','commission_employee_type' => 'setter','commission_lead' => 'paid_leads','commission_payment_type' => 'salary_commission','first_lead' => '2.00','second_lead' => '2.00','third_lead' => '2.00','fourth_lead' => '2.00','fifth_lead' => '2.00','onward_lead' => '2.00','hs_deal_name' => 'AUFTRAG GEWONNEN - MIT SETTER','status_id' => '3','created_at' => '2021-12-26 14:19:33','updated_at' => '2021-12-26 21:52:15'),
          array('id' => '4','name' => 'Setter - Salary + Commission - Person makes the deal alone','slug' => 'setter-salary-commission-freelancer-person-makes-the-deal-alone','commission_employee_type' => 'setter','commission_lead' => 'paid_leads_individual','commission_payment_type' => 'salary_commission','first_lead' => '6.00','second_lead' => '6.00','third_lead' => '6.00','fourth_lead' => '6.00','fifth_lead' => '6.00','onward_lead' => '6.00','hs_deal_name' => 'AUFTRAG GEWONNEN - OHNE SETTER','status_id' => '3','created_at' => '2021-12-26 14:39:05','updated_at' => '2021-12-26 21:53:09'),
          array('id' => '5','name' => 'Closer Freelancer - Normal Setting & Closing Process','slug' => 'closer-freelancer-normal-setting-and-closing-process','commission_employee_type' => 'closer','commission_lead' => 'paid_leads','commission_payment_type' => 'commission','first_lead' => '10.00','second_lead' => '10.00','third_lead' => '10.00','fourth_lead' => '10.00','fifth_lead' => '10.00','onward_lead' => '10.00','hs_deal_name' => 'AUFTRAG GEWONNEN - MIT SETTER','status_id' => '3','created_at' => '2021-12-26 14:19:33','updated_at' => '2021-12-26 21:55:20'),
          array('id' => '6','name' => 'Closer - Freelancer - Person makes the deal alone','slug' => 'closer-freelancer-person-makes-the-deal-alone','commission_employee_type' => 'closer','commission_lead' => 'paid_leads_individual','commission_payment_type' => 'commission','first_lead' => '15.00','second_lead' => '15.00','third_lead' => '15.00','fourth_lead' => '15.00','fifth_lead' => '15.00','onward_lead' => '15.00','hs_deal_name' => 'AUFTRAG GEWONNEN - OHNE SETTER','status_id' => '3','created_at' => '2021-12-26 14:39:05','updated_at' => '2021-12-26 15:03:50'),
          array('id' => '7','name' => 'Closer Salary + Commission - Normal Setting & Closing Process','slug' => 'closer-salary-commission-normal-setting-and-closing-process','commission_employee_type' => 'closer','commission_lead' => 'paid_leads','commission_payment_type' => 'salary_commission','first_lead' => '4.00','second_lead' => '4.00','third_lead' => '4.00','fourth_lead' => '4.00','fifth_lead' => '4.00','onward_lead' => '4.00','hs_deal_name' => 'AUFTRAG GEWONNEN - MIT SETTER','status_id' => '3','created_at' => '2021-12-26 14:19:33','updated_at' => '2021-12-26 22:38:38'),
          array('id' => '8','name' => 'Closer - Salary + Commission - Person makes the deal alone','slug' => 'closer-salary-commission-freelancer-person-makes-the-deal-alone','commission_employee_type' => 'closer','commission_lead' => 'paid_leads_individual','commission_payment_type' => 'salary_commission','first_lead' => '6.00','second_lead' => '6.00','third_lead' => '6.00','fourth_lead' => '6.00','fifth_lead' => '6.00','onward_lead' => '6.00','hs_deal_name' => 'AUFTRAG GEWONNEN - OHNE SETTER','status_id' => '3','created_at' => '2021-12-26 14:39:05','updated_at' => '2021-12-26 21:57:37'),
          array('id' => '9','name' => 'Closer Freelancer - Selling an existing customer another product','slug' => 'closer-freelancer-selling-an-existing-customer-another-product','commission_employee_type' => 'closer','commission_lead' => 'customer_leads','commission_payment_type' => 'commission','first_lead' => '10.00','second_lead' => '10.00','third_lead' => '10.00','fourth_lead' => '10.00','fifth_lead' => '10.00','onward_lead' => '10.00','hs_deal_name' => 'AUFTRAG GEWONNEN - MIT SETTER','status_id' => '3','created_at' => '2021-12-26 14:19:33','updated_at' => '2021-12-26 22:30:53'),
          array('id' => '10','name' => 'Closer Salary+Commission - Selling an existing customer another product','slug' => 'closer-salary-commission-selling-an-existing-customer-another-product','commission_employee_type' => 'closer','commission_lead' => 'customer_leads','commission_payment_type' => 'salary_commission','first_lead' => '4.00','second_lead' => '4.00','third_lead' => '4.00','fourth_lead' => '4.00','fifth_lead' => '4.00','onward_lead' => '4.00','hs_deal_name' => 'AUFTRAG GEWONNEN - OHNE SETTER','status_id' => '3','created_at' => '2021-12-26 14:39:05','updated_at' => '2021-12-26 22:31:38'),
          array('id' => '11','name' => 'Setter Freelancer - Selling an existing customer another product','slug' => 'Setter Freelancer - Selling an existing customer another product','commission_employee_type' => 'setter','commission_lead' => 'customer_leads','commission_payment_type' => 'commission','first_lead' => '10.00','second_lead' => '10.00','third_lead' => '10.00','fourth_lead' => '10.00','fifth_lead' => '10.00','onward_lead' => '10.00','hs_deal_name' => 'AUFTRAG GEWONNEN - MIT SETTER','status_id' => '3','created_at' => '2021-12-26 14:19:33','updated_at' => '2021-12-26 22:39:41'),
          array('id' => '12','name' => 'Setter Salary + Commission - Selling an existing customer another product','slug' => 'setter-salary-commission-selling-an-existing-customer-another-product','commission_employee_type' => 'setter','commission_lead' => 'customer_leads','commission_payment_type' => 'salary_commission','first_lead' => '4.00','second_lead' => '4.00','third_lead' => '4.00','fourth_lead' => '4.00','fifth_lead' => '4.00','onward_lead' => '4.00','hs_deal_name' => 'AUFTRAG GEWONNEN - OHNE SETTER','status_id' => '3','created_at' => '2021-12-26 14:39:05','updated_at' => '2021-12-26 22:40:21'),
          array('id' => '13','name' => 'Setter - Freelancer - Lead Generation','slug' => 'setter-freelancer-lead-generation','commission_employee_type' => 'setter','commission_lead' => 'cold_leads','commission_payment_type' => 'commission','first_lead' => '10.00','second_lead' => '11.00','third_lead' => '12.00','fourth_lead' => '13.00','fifth_lead' => '14.00','onward_lead' => '15.00','hs_deal_name' => 'AUFTRAG GEWONNEN - MIT SETTER','status_id' => '3','created_at' => '2021-12-26 14:56:16','updated_at' => '2021-12-26 14:56:16'),
          array('id' => '14','name' => 'Setter - Freelancer - Lead Generation + closing the Deal','slug' => 'setter-freelancer-lead-generation-closing-the-deal','commission_employee_type' => 'setter','commission_lead' => 'cold_leads_individual','commission_payment_type' => 'commission','first_lead' => '20.00','second_lead' => '21.00','third_lead' => '22.00','fourth_lead' => '23.00','fifth_lead' => '24.00','onward_lead' => '25.00','hs_deal_name' => 'AUFTRAG GEWONNEN - OHNE SETTER','status_id' => '3','created_at' => '2021-12-26 15:14:14','updated_at' => '2021-12-26 22:41:03'),
          array('id' => '15','name' => 'Closer - Freelancer - Lead Generation','slug' => 'closer-freelancer-lead-generation','commission_employee_type' => 'closer','commission_lead' => 'cold_leads','commission_payment_type' => 'commission','first_lead' => '10.00','second_lead' => '11.00','third_lead' => '12.00','fourth_lead' => '13.00','fifth_lead' => '14.00','onward_lead' => '15.00','hs_deal_name' => 'AUFTRAG GEWONNEN - MIT SETTER','status_id' => '3','created_at' => '2021-12-26 14:56:16','updated_at' => '2021-12-26 22:45:59'),
          array('id' => '16','name' => 'Closer - Freelancer - Lead Generation + closing the Deal','slug' => 'closer-freelancer-lead-generation-closing-the-deal','commission_employee_type' => 'closer','commission_lead' => 'cold_leads_individual','commission_payment_type' => 'commission','first_lead' => '20.00','second_lead' => '21.00','third_lead' => '22.00','fourth_lead' => '23.00','fifth_lead' => '24.00','onward_lead' => '25.00','hs_deal_name' => 'AUFTRAG GEWONNEN - OHNE SETTER','status_id' => '3','created_at' => '2021-12-26 15:14:14','updated_at' => '2021-12-26 22:45:30'),
          array('id' => '17','name' => 'Setter - Salary + Commission - Lead Generation','slug' => 'setter-salary-commission-lead-generation','commission_employee_type' => 'setter','commission_lead' => 'cold_leads','commission_payment_type' => 'salary_commission','first_lead' => '4.00','second_lead' => '4.50','third_lead' => '5.00','fourth_lead' => '5.50','fifth_lead' => '6.00','onward_lead' => '6.50','hs_deal_name' => 'AUFTRAG GEWONNEN - MIT SETTER','status_id' => '3','created_at' => '2021-12-26 14:56:16','updated_at' => '2021-12-26 22:54:03'),
          array('id' => '18','name' => 'Setter - Salary + Commission - Lead Generation + closing the Deal','slug' => 'setter-salary-commission-lead-generation-closing-the-deal','commission_employee_type' => 'setter','commission_lead' => 'cold_leads_individual','commission_payment_type' => 'salary_commission','first_lead' => '9.00','second_lead' => '9.50','third_lead' => '10.00','fourth_lead' => '10.50','fifth_lead' => '11.00','onward_lead' => '11.50','hs_deal_name' => 'AUFTRAG GEWONNEN - OHNE SETTER','status_id' => '3','created_at' => '2021-12-26 15:14:14','updated_at' => '2021-12-26 22:54:12'),
          array('id' => '19','name' => 'Closer - Salary + Commission - Lead Generation','slug' => 'closer-salary-commission-lead-generation','commission_employee_type' => 'closer','commission_lead' => 'cold_leads','commission_payment_type' => 'salary_commission','first_lead' => '4.00','second_lead' => '4.50','third_lead' => '5.00','fourth_lead' => '5.50','fifth_lead' => '6.00','onward_lead' => '6.50','hs_deal_name' => 'AUFTRAG GEWONNEN - MIT SETTER','status_id' => '3','created_at' => '2021-12-26 14:56:16','updated_at' => '2021-12-26 22:47:06'),
          array('id' => '20','name' => 'Closer - Salary + Commission - Lead Generation + closing the Deal','slug' => 'closer-salary-commission-lead-generation-closing-the-deal','commission_employee_type' => 'closer','commission_lead' => 'cold_leads_individual','commission_payment_type' => 'salary_commission','first_lead' => '9.00','second_lead' => '9.50','third_lead' => '10.00','fourth_lead' => '10.50','fifth_lead' => '11.00','onward_lead' => '11.50','hs_deal_name' => 'AUFTRAG GEWONNEN - OHNE SETTER','status_id' => '3','created_at' => '2021-12-26 15:14:14','updated_at' => '2021-12-28 06:49:33')
          )
      );
    }
}
