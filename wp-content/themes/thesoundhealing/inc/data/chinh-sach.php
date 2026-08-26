<?php

/**
 * Nội dung trang "Chính sách & Điều khoản" (bản VN + EN).
 *
 * Đây là văn bản pháp lý nộp kèm hồ sơ BCT nên để trong code thay vì DB: deploy
 * là có ngay cả 2 ngôn ngữ, không phụ thuộc người sửa trong wp-admin và không
 * thể vô tình bị xoá/đổi. Muốn sửa câu chữ thì sửa file này rồi deploy lại.
 *
 * `id` của 5 mục cố định và GIỐNG NHAU ở cả 2 ngôn ngữ để link neo (#bao-mat…)
 * dùng chung được cho /chinh-sach/ và /en/policies/.
 *
 * Cấu trúc mỗi block:
 *   ['h' => 'Tiêu đề nhỏ', 'parts' => [ ['p', 'đoạn văn'], ['ul', [...]], ['ol', [...]] ]]
 *
 * @return array{vi: array, en: array}
 */

defined('ABSPATH') || exit;

return [

    // ─────────────────────────────────────────────────────────────────────
    'vi' => [
        'title'     => 'Chính sách & Điều khoản',
        'intro'     => 'Trang này tập hợp đầy đủ các chính sách và điều khoản áp dụng khi bạn đăng ký khóa học, đặt buổi trải nghiệm và thanh toán trên thesoundhealing.vn.',
        'toc_label' => 'Mục lục',
        'note'      => 'Cập nhật: Chính sách có thể được điều chỉnh; bản cập nhật được đăng công khai trên trang này và có hiệu lực kể từ thời điểm đăng.',
        'sections'  => [

            [
                'id'    => 'bao-mat',
                'title' => '1. Chính sách bảo mật thông tin',
                'blocks' => [
                    ['h' => '1.1. Mục đích thu thập', 'parts' => [
                        ['p', 'Công ty Cổ phần Healiverse ("chúng tôi") thu thập thông tin của khách hàng nhằm: tiếp nhận và xử lý đăng ký khóa học, đặt buổi trải nghiệm; xác nhận, nhắc và điều phối lịch; xử lý thanh toán và xuất chứng từ; chăm sóc, hỗ trợ và giải quyết khiếu nại; cải thiện chất lượng dịch vụ; và gửi thông tin ưu đãi khi khách hàng đồng ý nhận.'],
                    ]],
                    ['h' => '1.2. Phạm vi và loại thông tin thu thập', 'parts' => [
                        ['ul', [
                            'Thông tin định danh và liên hệ: họ tên, số điện thoại, email, địa chỉ (khi cần xuất hóa đơn hoặc giao nhận).',
                            'Thông tin giao dịch: nội dung khóa học/buổi đã đặt, lịch, phương thức và trạng thái thanh toán.',
                            'Thông tin trao đổi: nội dung khách chủ động cung cấp khi đăng ký, tư vấn, hỗ trợ.',
                            'Thông tin kỹ thuật (cookies): địa chỉ IP, loại trình duyệt, trang đã xem, nhằm giúp website vận hành và cải thiện trải nghiệm. Khách có thể tắt cookies trong trình duyệt, một số tính năng có thể bị ảnh hưởng.',
                        ]],
                    ]],
                    ['h' => '1.3. Cơ sở pháp lý và sự đồng ý', 'parts' => [
                        ['p', 'Chúng tôi xử lý dữ liệu cá nhân trên cơ sở sự đồng ý của khách hàng và để thực hiện giao dịch khách đã yêu cầu, phù hợp Nghị định 13/2023/NĐ-CP về bảo vệ dữ liệu cá nhân. Khi khách gửi thông tin đăng ký/đặt lịch, khách đồng ý cho chúng tôi thu thập và xử lý dữ liệu theo chính sách này.'],
                    ]],
                    ['h' => '1.4. Cách sử dụng thông tin', 'parts' => [
                        ['p', 'Thông tin chỉ được dùng cho các mục đích nêu tại mục 1.1. Chúng tôi không sử dụng thông tin khách hàng cho mục đích khác nếu chưa được khách đồng ý.'],
                    ]],
                    ['h' => '1.5. Chia sẻ thông tin cho bên thứ ba', 'parts' => [
                        ['p', 'Chúng tôi không mua, bán hoặc trao đổi thông tin cá nhân của khách vì mục đích thương mại. Thông tin chỉ được chia sẻ trong phạm vi cần thiết với: đơn vị cung cấp dịch vụ thanh toán, đối tác vận hành/hạ tầng website, đơn vị vận chuyển (nếu có giao nhận vật phẩm); và cơ quan nhà nước có thẩm quyền khi có yêu cầu hợp pháp.'],
                    ]],
                    ['h' => '1.6. Thời gian lưu trữ', 'parts' => [
                        ['p', 'Thông tin được lưu trong thời gian cần thiết để phục vụ các mục đích trên, đến khi khách hàng yêu cầu xóa hoặc khi chúng tôi không còn nhu cầu sử dụng, trừ trường hợp pháp luật yêu cầu lưu lâu hơn.'],
                    ]],
                    ['h' => '1.7. Bảo mật thông tin', 'parts' => [
                        ['p', 'Chúng tôi áp dụng các biện pháp kỹ thuật và quản lý hợp lý để bảo vệ dữ liệu cá nhân khỏi việc truy cập, sử dụng, chỉnh sửa trái phép. Việc truyền dữ liệu qua internet không thể an toàn tuyệt đối; khách hàng tự bảo mật thông tin đăng nhập (nếu có) của mình.'],
                    ]],
                    ['h' => '1.8. Quyền của khách hàng (chủ thể dữ liệu)', 'parts' => [
                        ['p', 'Theo Nghị định 13/2023/NĐ-CP, khách hàng có quyền: được biết về việc xử lý dữ liệu; đồng ý hoặc rút lại sự đồng ý; truy cập, chỉnh sửa, cập nhật; yêu cầu xóa hoặc hạn chế xử lý; phản đối xử lý; và khiếu nại theo quy định. Để thực hiện, khách liên hệ theo mục 1.9.'],
                    ]],
                    ['h' => '1.9. Đơn vị thu thập và quản lý thông tin', 'parts' => [
                        ['p', 'Công ty Cổ phần Healiverse. Địa chỉ: 104/20 Mai Thị Lựu, Phường Tân Định, Thành phố Hồ Chí Minh. Hotline: 0939 624 684 - 0906 502 582. Email: admin@thesoundhealing.vn.'],
                    ]],
                ],
            ],

            [
                'id'    => 'dat-thanh-toan',
                'title' => '2. Hướng dẫn đặt và thanh toán',
                'blocks' => [
                    ['h' => '2.1. Các bước đặt khóa học / buổi trải nghiệm', 'parts' => [
                        ['ol', [
                            'Chọn khóa học hoặc buổi trải nghiệm trên website.',
                            'Chọn khung giờ và điền thông tin đăng ký (họ tên, số điện thoại, email).',
                            'Kiểm tra lại thông tin và chọn phương thức thanh toán.',
                            'Hoàn tất thanh toán.',
                            'Nhận email hoặc tin nhắn xác nhận kèm thông tin lịch và hướng dẫn tham dự.',
                        ]],
                    ]],
                    ['h' => '2.2. Phương thức thanh toán', 'parts' => [
                        ['p', 'Chuyển khoản ngân hàng:'],
                        ['ul', [
                            'Chủ tài khoản: CÔNG TY CỔ PHẦN HEALIVERSE',
                            'Ngân hàng: ACB (NHTMCP Á Châu) - CN Lê Ngô Cát',
                            'Số tài khoản: 66606898',
                            'Nội dung chuyển khoản: HEAL [Họ tên] [Số điện thoại]',
                        ]],
                        ['p', 'Thanh toán trực tiếp tại địa điểm tổ chức (nếu áp dụng).'],
                    ]],
                    ['h' => '2.3. Xác nhận đơn và chứng từ', 'parts' => [
                        ['p', 'Sau khi khách hoàn tất thanh toán, hệ thống gửi xác nhận qua email/điện thoại trong vòng 2 giờ. Khách có nhu cầu xuất hóa đơn vui lòng cung cấp thông tin xuất hóa đơn khi đăng ký hoặc trong vòng 3 ngày kể từ khi thanh toán.'],
                    ]],
                    ['h' => '2.4. Giá và an toàn thanh toán', 'parts' => [
                        ['p', 'Giá dịch vụ được niêm yết công khai trên từng trang chi tiết (đã bao gồm các loại thuế, phí theo quy định nếu có). Mọi giao dịch thanh toán được thực hiện qua kênh chính thức của công ty; khách hàng vui lòng không chuyển khoản vào tài khoản cá nhân không được công bố trên website.'],
                    ]],
                ],
            ],

            [
                'id'    => 'cung-ung-dich-vu',
                'title' => '3. Chính sách cung ứng dịch vụ',
                'blocks' => [
                    ['h' => '3.1. Hình thức và địa điểm', 'parts' => [
                        ['p', 'Các khóa học và buổi trải nghiệm có thể được tổ chức trực tiếp tại công ty (104/20 Mai Thị Lựu, Phường Tân Định, TP. Hồ Chí Minh), tại địa điểm sự kiện do công ty chỉ định, hoặc trực tuyến (online), tùy chương trình và theo thông tin công bố trên trang chi tiết của chương trình.'],
                    ]],
                    ['h' => '3.2. Thời lượng và nội dung', 'parts' => [
                        ['p', 'Mỗi khóa học/buổi trải nghiệm có thời lượng, nội dung và lịch cụ thể ghi tại trang chi tiết sản phẩm (thời lượng phổ biến trung bình từ 60-120 phút/buổi). Số lượng người tham dự mỗi buổi có thể giới hạn theo mô tả.'],
                    ]],
                    ['h' => '3.3. Chuẩn bị, check-in và đến muộn', 'parts' => [
                        ['p', 'Khách vui lòng có mặt trước giờ bắt đầu 15 phút để ổn định chỗ. Trường hợp đến muộn, buổi vẫn kết thúc đúng giờ và công ty không kéo dài thời gian bù, nhằm không ảnh hưởng các khách hàng khác.'],
                    ]],
                    ['h' => '3.4. Xác nhận, nhắc lịch và thay đổi từ phía công ty', 'parts' => [
                        ['p', 'Công ty xác nhận lịch sau khi khách hoàn tất thanh toán và nhắc lịch trước buổi qua email/điện thoại. Trường hợp bất khả kháng phải đổi/hoãn, công ty thông báo sớm nhất có thể và sắp lịch thay thế hoặc hoàn tiền theo mục 4.'],
                    ]],
                    ['h' => '3.5. Giấy chứng nhận (đối với khóa học)', 'parts' => [
                        ['p', 'Với các khóa đào tạo kỹ năng, học viên hoàn thành được cấp Giấy chứng nhận hoàn thành khóa học do Công ty Cổ phần Healiverse cấp. Giấy chứng nhận này xác nhận học viên đã hoàn thành chương trình đào tạo kỹ năng của công ty, không phải văn bằng/chứng chỉ thuộc hệ thống giáo dục quốc dân.'],
                    ]],
                ],
            ],

            [
                'id'    => 'hoan-huy-doi-lich',
                'title' => '4. Chính sách hoàn, hủy, đổi lịch',
                'blocks' => [
                    ['h' => '4.1. Đổi/dời lịch và chuyển nhượng (từ phía khách)', 'parts' => [
                        ['p', 'Khách có thể dời lịch hoặc chuyển nhượng cho người khác, nhưng không hoàn tiền khi hủy dưới mọi hình thức. Điều kiện dời lịch: thông báo trước tối thiểu 2 giờ so với giờ hẹn. Khách được hỗ trợ dời lịch 01 (một) lần hoặc chuyển nhượng người thụ hưởng, và bảo lưu giá trị đã thanh toán trong thời hạn 03 (ba) tháng kể từ ngày dời. Sau thời hạn này, nếu không sử dụng, quyền lợi hết hiệu lực và khoản thanh toán không được hoàn lại.'],
                    ]],
                    ['h' => '4.2. Vắng mặt không báo trước (No-show)', 'parts' => [
                        ['p', 'Khách vắng mặt mà không thông báo trước được hỗ trợ đặt lại lịch 01 (một) lần trong thời hạn 03 (ba) tháng kể từ ngày hẹn ban đầu. Sau thời hạn này, quyền sử dụng dịch vụ tự động hết hiệu lực, không hoàn tiền hoặc gia hạn.'],
                    ]],
                    ['h' => '4.3. Trường hợp công ty hủy hoặc hoãn', 'parts' => [
                        ['p', 'Nếu HEALIVERSE hủy một buổi trải nghiệm hoặc khóa học, khách được chọn một trong các phương án: chuyển sang một lịch khác; bảo lưu toàn bộ giá trị đã thanh toán để sử dụng sau; hoặc nhận hoàn lại 100% số tiền đã thanh toán.'],
                    ]],
                    ['h' => '4.4. Cách yêu cầu và thời gian xử lý hoàn tiền', 'parts' => [
                        ['p', 'Với các trường hợp được hoàn tiền (mục 4.3), khách gửi yêu cầu qua hotline hoặc email tại mục 1.9, kèm thông tin đơn và tài khoản nhận hoàn. Sau khi yêu cầu hợp lệ được xác nhận, công ty hoàn tiền trong vòng 30 ngày làm việc, về đúng phương thức/tài khoản khách đã thanh toán.'],
                    ]],
                ],
            ],

            [
                'id'    => 'dieu-khoan-su-dung',
                'title' => '5. Điều khoản sử dụng và điều kiện giao dịch chung',
                'blocks' => [
                    ['h' => '5.1. Phạm vi áp dụng', 'parts' => [
                        ['p', 'Điều khoản này áp dụng cho khách hàng truy cập, đăng ký, mua khóa học và đặt buổi trải nghiệm trên thesoundhealing.vn. Khi sử dụng website và dịch vụ, khách được xem là đã đọc, hiểu và đồng ý với các điều khoản này.'],
                    ]],
                    ['h' => '5.2. Quyền và nghĩa vụ của khách hàng', 'parts' => [
                        ['p', 'Cung cấp thông tin chính xác khi đăng ký; thanh toán đầy đủ và đúng hạn; tuân thủ hướng dẫn tham dự và nội quy tại địa điểm; không sử dụng website vào mục đích trái pháp luật.'],
                    ]],
                    ['h' => '5.3. Quyền và nghĩa vụ của công ty', 'parts' => [
                        ['p', 'Cung cấp dịch vụ đúng mô tả đã công bố; bảo mật thông tin khách hàng theo mục 1; hỗ trợ khách trong quá trình sử dụng dịch vụ; có quyền từ chối hoặc dừng phục vụ trong trường hợp khách vi phạm điều khoản hoặc gây ảnh hưởng đến khách khác.'],
                    ]],
                    ['h' => '5.4. Sở hữu trí tuệ', 'parts' => [
                        ['p', 'Toàn bộ nội dung trên website (văn bản, hình ảnh, âm thanh, tài liệu khóa học, thương hiệu) thuộc quyền sở hữu của Công ty Cổ phần Healiverse hoặc bên cấp quyền. Khách không sao chép, phân phối, sử dụng lại vì mục đích thương mại khi chưa có sự đồng ý bằng văn bản.'],
                    ]],
                    ['h' => '5.5. Tính chất dịch vụ', 'parts' => [
                        ['p', 'Các khóa học và buổi trải nghiệm trên website mang tính chất giáo dục, thư giãn và trải nghiệm tinh thần. Đây không phải dịch vụ khám bệnh, chữa bệnh và không thay thế cho tư vấn, chẩn đoán hoặc điều trị y tế. Khách hàng có vấn đề sức khỏe nên tham khảo ý kiến bác sĩ trước khi tham dự.'],
                    ]],
                    ['h' => '5.6. Giới hạn trách nhiệm', 'parts' => [
                        ['p', 'Công ty nỗ lực cung cấp dịch vụ đúng cam kết. Công ty không chịu trách nhiệm cho các thiệt hại phát sinh do khách cung cấp thông tin sai, không tuân thủ hướng dẫn, hoặc do sự kiện bất khả kháng ngoài khả năng kiểm soát.'],
                    ]],
                    ['h' => '5.7. Giải quyết tranh chấp và luật áp dụng', 'parts' => [
                        ['p', 'Các giao dịch được điều chỉnh theo pháp luật Việt Nam. Tranh chấp phát sinh được ưu tiên giải quyết thông qua thương lượng; nếu không đạt kết quả sẽ đưa ra cơ quan có thẩm quyền tại Việt Nam giải quyết.'],
                    ]],
                ],
            ],
        ],
    ],

    // ─────────────────────────────────────────────────────────────────────
    'en' => [
        'title'     => 'Policies & Terms',
        'intro'     => 'This page brings together all policies and terms that apply when you register for a course, book an experience and make a payment on thesoundhealing.vn.',
        'toc_label' => 'Contents',
        'note'      => 'Updates: These policies may be revised; the updated version is posted on this page and takes effect from the time of posting.',
        'sections'  => [

            [
                'id'    => 'bao-mat',
                'title' => '1. Privacy Policy',
                'blocks' => [
                    ['h' => '1.1. Purpose of collection', 'parts' => [
                        ['p', 'HEALIVERSE Joint Stock Company ("we") collects customer information to: receive and process course registrations and experience bookings; confirm, remind and coordinate schedules; process payments and issue records; provide care, support and complaint resolution; improve service quality; and send offers when the customer opts in.'],
                    ]],
                    ['h' => '1.2. Scope and types of information', 'parts' => [
                        ['ul', [
                            'Identity and contact data: full name, phone, email, address (when needed for invoicing or delivery).',
                            'Transaction data: booked course/experience, schedule, payment method and status.',
                            'Communication data: information the customer voluntarily provides during registration, consultation or support.',
                            'Technical data (cookies): IP address, browser type, pages viewed, to operate the website and improve the experience. You may disable cookies in your browser; some features may be affected.',
                        ]],
                    ]],
                    ['h' => '1.3. Legal basis and consent', 'parts' => [
                        ['p', "We process personal data based on the customer's consent and to perform the transaction the customer requested, in line with Decree 13/2023/ND-CP on personal data protection. By submitting registration/booking information, you consent to our collection and processing of your data under this policy."],
                    ]],
                    ['h' => '1.4. How we use information', 'parts' => [
                        ['p', "Information is used only for the purposes in 1.1. We do not use customer information for other purposes without the customer's consent."],
                    ]],
                    ['h' => '1.5. Sharing with third parties', 'parts' => [
                        ['p', 'We do not buy, sell or trade customer personal data for commercial purposes. Data is shared only as necessary with: payment providers, website hosting/operation partners, delivery providers (if physical items are shipped); and competent state authorities upon lawful request.'],
                    ]],
                    ['h' => '1.6. Retention period', 'parts' => [
                        ['p', 'Information is kept for as long as necessary for the purposes above, until the customer requests deletion or we no longer need it, unless the law requires longer retention.'],
                    ]],
                    ['h' => '1.7. Data security', 'parts' => [
                        ['p', 'We apply reasonable technical and organizational measures to protect personal data against unauthorized access, use or alteration. Transmission over the internet is never fully secure; please keep your login credentials (if any) confidential.'],
                    ]],
                    ['h' => '1.8. Your rights (data subject)', 'parts' => [
                        ['p', 'Under Decree 13/2023/ND-CP, you have the right to: be informed about the processing; give or withdraw consent; access, correct and update; request deletion or restriction of processing; object to processing; and lodge a complaint. To exercise these rights, contact us via 1.9.'],
                    ]],
                    ['h' => '1.9. Data controller', 'parts' => [
                        ['p', 'HEALIVERSE Joint Stock Company. Address: 104/20 Mai Thi Luu, Tan Dinh Ward, Ho Chi Minh City. Hotline: 0939 624 684 - 0906 502 582. Email: admin@thesoundhealing.vn.'],
                    ]],
                ],
            ],

            [
                'id'    => 'dat-thanh-toan',
                'title' => '2. Booking & Payment',
                'blocks' => [
                    ['h' => '2.1. How to book a course / experience', 'parts' => [
                        ['ol', [
                            'Choose a course or experience on the website.',
                            'Select a time slot and fill in your details (full name, phone, email).',
                            'Review your information and choose a payment method.',
                            'Complete payment.',
                            'Receive a confirmation email/message with your schedule and attendance guidance.',
                        ]],
                    ]],
                    ['h' => '2.2. Payment methods', 'parts' => [
                        ['p', 'Bank transfer:'],
                        ['ul', [
                            'Account holder: HEALIVERSE JOINT STOCK COMPANY',
                            'Bank: ACB (Asia Commercial Bank) - Le Ngo Cat Branch',
                            'Account number: 66606898',
                            'Transfer note: HEAL [Full name] [Phone number]',
                        ]],
                        ['p', 'On-site payment at the venue (if applicable).'],
                    ]],
                    ['h' => '2.3. Order confirmation and records', 'parts' => [
                        ['p', 'After payment, the system sends a confirmation by email/phone within 2 hours. For an invoice, please provide invoicing details at registration or within 3 days of payment.'],
                    ]],
                    ['h' => '2.4. Pricing and payment safety', 'parts' => [
                        ['p', "Prices are publicly listed on each detail page (inclusive of applicable taxes/fees where required). All payments are made through the company's official channels; please do not transfer funds to any personal account not published on this website."],
                    ]],
                ],
            ],

            [
                'id'    => 'cung-ung-dich-vu',
                'title' => '3. Service Delivery',
                'blocks' => [
                    ['h' => '3.1. Format and location', 'parts' => [
                        ['p', "Courses and experiential sessions may be conducted in person at the Company's premises (104/20 Mai Thi Luu, Tan Dinh Ward, Ho Chi Minh City), at event venues designated by the Company, or online, depending on each program and as specified on the respective program's information page."],
                    ]],
                    ['h' => '3.2. Duration and content', 'parts' => [
                        ['p', 'Each course/experience has its own duration, content and schedule as specified on its detail page (typically 60 to 120 minutes per session). Group sizes may be limited and will be specified in the program description.'],
                    ]],
                    ['h' => '3.3. Preparation, check-in and late arrival', 'parts' => [
                        ['p', 'Please arrive 15 minutes before the start time to settle in. If you arrive late, the session still ends at the scheduled time and is not extended, out of respect for other guests.'],
                    ]],
                    ['h' => '3.4. Confirmation, reminders and changes by us', 'parts' => [
                        ['p', 'We confirm your schedule after payment and send a reminder before the session by email/phone. In force-majeure cases requiring a change, we notify you as early as possible and offer an alternative schedule or a refund per section 4.'],
                    ]],
                    ['h' => '3.5. Certificate (for courses)', 'parts' => [
                        ['p', "For skills-training courses, participants who complete the program receive a Certificate of Completion issued by HEALIVERSE Joint Stock Company. This certificate confirms completion of the company's skills-training program; it is not a diploma or certificate within the national education system."],
                    ]],
                ],
            ],

            [
                'id'    => 'hoan-huy-doi-lich',
                'title' => '4. Cancellation, Refund & Rescheduling',
                'blocks' => [
                    ['h' => '4.1. Rescheduling and transfer (by the customer)', 'parts' => [
                        ['p', 'Customers may reschedule or transfer their booking to another participant; however, no refunds are provided for cancellations under any circumstances. Rescheduling condition: notify the Company at least 2 hours before the scheduled time. The customer may reschedule once or transfer the booking to another participant, and the paid value is preserved for up to 03 (three) months from the rescheduling date. After this period, any unused booking automatically expires without refund.'],
                    ]],
                    ['h' => '4.2. No-show (failure to attend without prior notice)', 'parts' => [
                        ['p', 'Customers who do not attend without prior notice may be assisted in rescheduling once within 03 (three) months from the original session date. After this period, the booking automatically expires and is not eligible for a refund, extension, or further rescheduling.'],
                    ]],
                    ['h' => '4.3. If we cancel or postpone', 'parts' => [
                        ['p', 'If HEALIVERSE cancels a session or course, the customer may choose to: move to another schedule; keep the full paid value as a credit for later use; or receive a 100% refund of the amount paid.'],
                    ]],
                    ['h' => '4.4. How to request and refund processing time', 'parts' => [
                        ['p', 'For refund-eligible cases (section 4.3), submit a request via the hotline or email in section 1.9, with your booking details and the account for the refund. Once a valid request is confirmed, the Company processes the refund within 30 (thirty) business days, to the same method/account originally used, whenever possible.'],
                    ]],
                ],
            ],

            [
                'id'    => 'dieu-khoan-su-dung',
                'title' => '5. Terms of Use & General Transaction Conditions',
                'blocks' => [
                    ['h' => '5.1. Scope', 'parts' => [
                        ['p', 'These terms apply to customers who access, register, purchase courses and book experiences on thesoundhealing.vn. By using the website and services, you are deemed to have read, understood and agreed to these terms.'],
                    ]],
                    ['h' => '5.2. Customer rights and obligations', 'parts' => [
                        ['p', 'Provide accurate information when registering; pay in full and on time; follow attendance guidance and venue rules; do not use the website for unlawful purposes.'],
                    ]],
                    ['h' => '5.3. Company rights and obligations', 'parts' => [
                        ['p', 'Deliver services as described; protect customer information per section 1; support customers throughout the service; and we may decline or stop service where a customer breaches these terms or affects other guests.'],
                    ]],
                    ['h' => '5.4. Intellectual property', 'parts' => [
                        ['p', 'All website content (text, images, audio, course materials, brand) belongs to HEALIVERSE Joint Stock Company or its licensors. You may not copy, distribute or reuse it for commercial purposes without our written consent.'],
                    ]],
                    ['h' => '5.5. Nature of the services', 'parts' => [
                        ['p', 'The courses and experiences on this website are educational, relaxation and wellbeing experiences. They are not medical examination or treatment services and are not a substitute for professional medical advice, diagnosis or treatment. Customers with health concerns should consult a doctor before attending.'],
                    ]],
                    ['h' => '5.6. Limitation of liability', 'parts' => [
                        ['p', 'We strive to deliver services as committed. We are not liable for damages arising from inaccurate information provided by the customer, failure to follow guidance, or force-majeure events beyond our control.'],
                    ]],
                    ['h' => '5.7. Dispute resolution and governing law', 'parts' => [
                        ['p', 'Transactions are governed by the laws of Vietnam. Disputes are resolved first through negotiation; if unresolved, they are brought before the competent authority in Vietnam.'],
                    ]],
                ],
            ],
        ],
    ],
];
