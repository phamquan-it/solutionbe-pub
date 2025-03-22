<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use Carbon\Carbon;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Web Restful Service',
                'title_vi' => 'Dịch vụ Web Restful',
                'description' => 'With a flexible interface and optimized component system, web applications become more intuitive, user-friendly, and highly responsive.',
                'description_vi' => 'Với giao diện linh hoạt và hệ thống thành phần được tối ưu hóa, các ứng dụng web trở nên trực quan, thân thiện với người dùng và có độ phản hồi cao.',
                'image' => 'https://soluti0n.dev/restful-removebg-preview.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Linux for Development',
                'title_vi' => 'Linux cho phát triển',
                'description' => 'A leading choice for developers and businesses thanks to its superior stability, Linux offers great flexibility and strong support.',
                'description_vi' => 'Là lựa chọn hàng đầu cho lập trình viên và doanh nghiệp nhờ tính ổn định vượt trội, Linux cung cấp khả năng tùy chỉnh linh hoạt và hệ sinh thái hỗ trợ mạnh mẽ.',
                'image' => 'https://soluti0n.dev/linuxsetup-removebg.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'CLI Automation Tools',
                'title_vi' => 'Công cụ tự động hóa CLI',
                'description' => 'With high customization and flexibility, CLI tools enhance productivity, automate repetitive tasks, and improve workflow efficiency.',
                'description_vi' => 'Với các tính năng tùy chỉnh và linh hoạt, công cụ CLI giúp tăng năng suất, tự động hóa các tác vụ lặp đi lặp lại và cải thiện hiệu quả làm việc.',
                'image' => 'https://soluti0n.dev/automation_edited.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($posts as $post) {
            Post::updateOrCreate(['title' => $post['title']], $post);
        }
    }
}
