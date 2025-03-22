<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FAQ;

class FAQSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'What is your advertising platform, and how does it work?',
                'question_vi' => 'Nền tảng quảng cáo của bạn là gì và nó hoạt động như thế nào?',
                'answer' => 'Our platform helps businesses promote their products and services through targeted digital ads.',
                'answer_vi' => 'Nền tảng của chúng tôi giúp doanh nghiệp quảng bá sản phẩm và dịch vụ thông qua quảng cáo kỹ thuật số được nhắm mục tiêu.'
            ],
            [
                'question' => 'How can I get started with your advertising services?',
                'question_vi' => 'Làm cách nào để tôi có thể bắt đầu với dịch vụ quảng cáo của bạn?',
                'answer' => 'Simply sign up on our platform, choose your ad campaign type, set your budget, and upload your ad content.',
                'answer_vi' => 'Chỉ cần đăng ký trên nền tảng của chúng tôi, chọn loại chiến dịch quảng cáo, đặt ngân sách và tải nội dung quảng cáo lên.'
            ],
            [
                'question' => 'What are the pricing options for running ads?',
                'question_vi' => 'Các tùy chọn giá cho quảng cáo là gì?',
                'answer' => 'We offer flexible pricing models, including Cost Per Click (CPC), Cost Per Impression (CPM), and Cost Per Acquisition (CPA).',
                'answer_vi' => 'Chúng tôi cung cấp các mô hình giá linh hoạt, bao gồm Giá mỗi lần nhấp chuột (CPC), Giá mỗi lần hiển thị (CPM) và Giá mỗi lần mua hàng (CPA).'
            ],
            [
                'question' => 'How can I track the performance of my ads?',
                'question_vi' => 'Làm thế nào để tôi có thể theo dõi hiệu suất quảng cáo của mình?',
                'answer' => 'Our dashboard provides real-time analytics, including impressions, clicks, conversions, and ROI metrics.',
                'answer_vi' => 'Bảng điều khiển của chúng tôi cung cấp phân tích theo thời gian thực, bao gồm lượt hiển thị, số lần nhấp, chuyển đổi và chỉ số ROI.'
            ],
            [
                'question' => 'What kind of support do you provide?',
                'question_vi' => 'Bạn cung cấp loại hỗ trợ nào?',
                'answer' => 'We offer 24/7 customer support via live chat, email, and phone.',
                'answer_vi' => 'Chúng tôi cung cấp hỗ trợ khách hàng 24/7 qua trò chuyện trực tiếp, email và điện thoại.'
            ]
        ];

        // Insert data into the FAQ table
        foreach ($faqs as $faq) {
            FAQ::create($faq);
        }
    }
}
