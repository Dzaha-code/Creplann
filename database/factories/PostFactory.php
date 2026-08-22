<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Konten artikel dummy yang realistis tentang produktivitas.
     */
    private array $productivityTopics = [
        [
            'title'   => 'Cara Memulai Minggu yang Produktif dengan Weekly Planning',
            'excerpt' => 'Banyak orang kehilangan fokus di awal minggu karena tidak punya gambaran jelas tentang apa yang harus dikerjakan. Weekly planning adalah jawabannya.',
            'content' => "<p>Memulai minggu dengan perencanaan yang matang adalah salah satu kebiasaan yang membedakan orang produktif dari yang tidak. Ketika Anda duduk sejenak setiap Senin pagi dan menuliskan apa yang ingin dicapai minggu ini, Anda memberikan arah yang jelas bagi pikiran dan energi Anda.</p>\n\n<h2>Mengapa Weekly Planning Penting?</h2>\n<p>Tanpa perencanaan, kita cenderung bereaksi terhadap hal-hal yang datang secara acak — email masuk, permintaan mendadak, notifikasi yang tidak ada habisnya. Weekly planning membalik pola ini: Anda memilih secara proaktif apa yang penting, bukan sekadar merespons apa yang mendesak.</p>\n\n<h2>Langkah Memulai Weekly Planning di CrePlann</h2>\n<p>Buka halaman Schedule, pilih rentang minggu yang ingin Anda rencanakan, lalu mulai mengisi jadwal satu per satu. Pastikan setiap jadwal memiliki judul yang jelas, waktu mulai dan selesai, serta prioritas yang realistis.</p>\n\n<p>Setelah mengisi jadwal, gunakan fitur Generate Todo untuk secara otomatis mengubah jadwal menjadi item todo yang bisa Anda centang satu per satu. Ini membantu Anda memecah tugas besar menjadi langkah-langkah yang lebih kecil dan terukur.</p>\n\n<h2>Tips Agar Weekly Planning Berhasil</h2>\n<ul>\n<li>Lakukan review singkat setiap Jumat sore untuk mengevaluasi minggu yang berjalan.</li>\n<li>Jangan isi jadwal terlalu penuh — sisakan buffer time untuk hal tak terduga.</li>\n<li>Prioritaskan maksimal 3 hal penting per hari agar fokus tetap terjaga.</li>\n</ul>\n\n<p>Konsistensi adalah kunci. Coba lakukan weekly planning selama 4 minggu berturut-turut dan perhatikan perbedaannya pada produktivitas dan ketenangan pikiran Anda.</p>",
        ],
        [
            'title'   => 'Teknik Time Blocking: Jadwalkan Waktu, Bukan Tugas',
            'excerpt' => 'Time blocking bukan tentang mengisi kalender sampai penuh. Ini tentang mengalokasikan waktu secara intentional untuk pekerjaan yang benar-benar penting bagi Anda.',
            'content' => "<p>Time blocking adalah teknik manajemen waktu di mana Anda mengalokasikan blok waktu spesifik untuk jenis pekerjaan tertentu. Berbeda dengan to-do list biasa yang hanya mencatat apa yang perlu dilakukan, time blocking menentukan <em>kapan</em> sesuatu akan dikerjakan.</p>\n\n<h2>Perbedaan Time Blocking dan To-Do List</h2>\n<p>To-do list memberi tahu Anda apa yang harus dilakukan. Time blocking memaksa Anda untuk jujur tentang berapa lama sesuatu benar-benar membutuhkan waktu. Ketika Anda memblokir 2 jam untuk menulis laporan, Anda berkomitmen pada waktu itu — bukan sekadar berharap Anda akan sempat mengerjakannya di sela-sela waktu luang.</p>\n\n<h2>Cara Menerapkan Time Blocking di CrePlann</h2>\n<p>Gunakan fitur Schedule untuk membuat blok waktu. Beri warna berbeda untuk kategori pekerjaan yang berbeda: merah untuk pekerjaan deep work, hijau untuk meeting, kuning untuk administrative tasks. Dengan tampilan weekly grid, Anda bisa langsung melihat bagaimana distribusi waktu Anda sepanjang minggu.</p>\n\n<h2>Blok Waktu yang Tidak Boleh Dilanggar</h2>\n<p>Tentukan beberapa blok waktu yang tidak bisa diganggu gugat. Misalnya, setiap hari Selasa dan Kamis pukul 09.00–11.00 adalah waktu untuk pekerjaan paling penting. Selama blok ini, matikan notifikasi dan tolak meeting yang bisa dijadwalkan ulang.</p>",
        ],
        [
            'title'   => 'Mengapa To-Do List Anda Selalu Tidak Selesai (dan Cara Memperbaikinya)',
            'excerpt' => 'Jika to-do list Anda terus bertambah panjang tanpa banyak yang tercentang, masalahnya bukan pada kurangnya disiplin — melainkan pada cara Anda menulis daftarnya.',
            'content' => "<p>Hampir semua orang pernah mengalaminya: to-do list yang menumpuk, item yang sama muncul berhari-hari berturut-turut, dan perasaan bersalah yang terus menghantui. Masalahnya seringkali bukan pada Anda, tapi pada bagaimana to-do list itu dibuat.</p>\n\n<h2>Kesalahan Umum dalam Membuat To-Do List</h2>\n<p><strong>1. Item terlalu besar dan ambigu.</strong> \"Selesaikan proyek\" bukanlah to-do yang actionable. Tidak ada yang tahu dari mana harus mulai. Pecah menjadi: \"Tulis outline bab 1\", \"Riset referensi untuk bagian X\", dan seterusnya.</p>\n\n<p><strong>2. Tidak ada due date.</strong> Tanpa tenggat waktu, otak kita secara alami menunda hal yang tidak mendesak. Setiap item to-do sebaiknya memiliki tanggal target penyelesaian.</p>\n\n<p><strong>3. Mencampur semua konteks.</strong> Item pekerjaan, belanja, dan urusan pribadi dalam satu daftar menciptakan cognitive load yang tidak perlu. Pisahkan berdasarkan konteks.</p>\n\n<h2>Cara Memperbaikinya dengan CrePlann</h2>\n<p>Saat membuat todo baru, selalu isi due date. Gunakan filter tab di halaman Todo untuk melihat todo berdasarkan status: semua, aktif, atau selesai. Gunakan fitur Generate Todo dari Schedule untuk membuat todo yang langsung terhubung dengan jadwal spesifik — ini membantu memberikan konteks kapan sesuatu harus dikerjakan.</p>",
        ],
        [
            'title'   => 'Kekuatan Catatan Harian untuk Produktivitas Jangka Panjang',
            'excerpt' => 'Mencatat bukan sekadar menyimpan informasi. Menulis secara reguler membantu otak memproses pengalaman, mempertajam pemikiran, dan menemukan pola yang tidak terlihat.',
            'content' => "<p>Banyak orang sukses dunia — dari Marcus Aurelius hingga Richard Feynman — memiliki kebiasaan mencatat yang konsisten. Bukan karena mereka ingin meninggalkan warisan tulisan, melainkan karena menulis membantu mereka berpikir lebih jernih.</p>\n\n<h2>Manfaat Mencatat Secara Reguler</h2>\n<p>Ketika Anda menuliskan pikiran, Anda memaksa otak untuk mengorganisasikannya menjadi kalimat yang koheren. Proses ini sering kali mengungkapkan insight yang tidak akan muncul jika Anda hanya berpikir tanpa menuliskannya. Catatan juga berfungsi sebagai ekstensi memori — Anda tidak perlu mengingat semua detail jika sudah tertulis.</p>\n\n<h2>Jenis Catatan yang Berguna</h2>\n<ul>\n<li><strong>Daily log:</strong> Apa yang dilakukan hari ini, apa yang dipelajari, apa yang perlu ditindaklanjuti.</li>\n<li><strong>Capture notes:</strong> Ide yang muncul tiba-tiba dan perlu disimpan sebelum terlupakan.</li>\n<li><strong>Reference notes:</strong> Ringkasan buku, artikel, atau materi pembelajaran.</li>\n<li><strong>Project notes:</strong> Semua hal terkait proyek spesifik dalam satu tempat.</li>\n</ul>\n\n<h2>Menggunakan Notes di CrePlann</h2>\n<p>Buat kategori yang sesuai dengan jenis catatan Anda. Misalnya: \"Kerja\", \"Belajar\", \"Pribadi\", \"Ide\". Setiap kali ada sesuatu yang perlu dicatat, buka Notes, pilih kategori yang tepat, dan tulis. Jangan terlalu perfeksionis — lebih baik catatan singkat yang ada daripada catatan sempurna yang tidak pernah ditulis.</p>",
        ],
        [
            'title'   => 'Cara Membangun Sistem Produktivitas yang Tidak Bergantung pada Motivasi',
            'excerpt' => 'Motivasi datang dan pergi. Sistem yang baik bekerja bahkan di hari-hari ketika Anda tidak merasa ingin melakukan apa pun.',
            'content' => "<p>Salah satu mitos terbesar tentang produktivitas adalah bahwa orang-orang produktif selalu termotivasi dan bersemangat. Kenyataannya, mereka juga mengalami hari-hari berat, rasa malas, dan kejenuhan. Perbedaannya: mereka memiliki sistem yang berjalan bahkan tanpa motivasi.</p>\n\n<h2>Sistem vs. Motivasi</h2>\n<p>Motivasi adalah emosi — tidak stabil dan tidak bisa diandalkan sebagai fondasi produktivitas jangka panjang. Sistem adalah serangkaian kebiasaan dan ritual yang berjalan secara otomatis. Ketika sistem sudah tertanam, Anda tidak perlu memutuskan apakah akan melakukannya atau tidak — Anda hanya menjalankannya.</p>\n\n<h2>Elemen Sistem Produktivitas yang Efektif</h2>\n<p><strong>Ritual pagi:</strong> Luangkan 15 menit setiap pagi untuk melihat jadwal hari ini, mengecek todo aktif, dan menentukan 1–3 hal terpenting yang harus diselesaikan hari ini.</p>\n\n<p><strong>Weekly review:</strong> Setiap Jumat atau Minggu, evaluasi minggu yang berjalan. Apa yang berhasil? Apa yang tidak? Pelajaran apa yang bisa dibawa ke minggu depan?</p>\n\n<p><strong>Capture inbox:</strong> Satu tempat untuk menampung semua hal yang masuk — tugas, ide, informasi — sebelum diproses dan ditempatkan di tempat yang tepat. Di CrePlann, gunakan Notes sebagai capture inbox Anda.</p>\n\n<h2>Memulai Pelan-Pelan</h2>\n<p>Jangan mencoba membangun sistem sempurna sekaligus. Mulai dengan satu kebiasaan kecil — misalnya, setiap pagi buka CrePlann dan lihat jadwal hari ini. Setelah itu menjadi otomatis, tambahkan elemen sistem berikutnya.</p>",
        ],
        [
            'title'   => 'Deep Work: Cara Bekerja dengan Fokus Penuh di Era Distraksi',
            'excerpt' => 'Di era notifikasi non-stop dan open office, kemampuan untuk bekerja dengan fokus penuh selama beberapa jam menjadi keahlian yang langka — dan sangat berharga.',
            'content' => "<p>Cal Newport, dalam bukunya \"Deep Work\", mendefinisikan deep work sebagai aktivitas profesional yang dilakukan dalam kondisi bebas distraksi dengan konsentrasi penuh, mendorong kemampuan kognitif Anda ke batas maksimal. Hasilnya: nilai yang sulit direplikasi dan keterampilan yang terus berkembang.</p>\n\n<h2>Mengapa Deep Work Semakin Langka?</h2>\n<p>Open plan office, budaya selalu-online, dan ekspektasi respons email cepat telah menciptakan lingkungan yang sangat tidak ramah untuk fokus mendalam. Namun justru karena langka, kemampuan deep work menjadi semakin bernilai di pasar kerja modern.</p>\n\n<h2>Membangun Ritual Deep Work</h2>\n<p>Tentukan waktu spesifik untuk deep work — idealnya di pagi hari ketika energi masih segar. Blokir waktu ini di Schedule CrePlann Anda sebagai \"Protected Time\" dengan prioritas tinggi. Selama sesi ini, matikan semua notifikasi, tutup tab yang tidak perlu, dan beritahu rekan kerja bahwa Anda tidak bisa diganggu.</p>\n\n<h2>Durasi Optimal</h2>\n<p>Mulai dengan sesi 90 menit. Ini cukup panjang untuk masuk ke kondisi flow, namun tidak terlalu lama hingga melelahkan. Setelah istirahat singkat 15–30 menit, Anda bisa memulai sesi berikutnya jika energi masih ada.</p>\n\n<p>Catat setiap sesi deep work yang berhasil di Notes CrePlann — ini membantu Anda melihat pola kapan Anda paling produktif dan apa kondisi yang mendukungnya.</p>",
        ],
        [
            'title'   => 'Kenapa Review Mingguan adalah Kebiasaan Produktivitas Paling Underrated',
            'excerpt' => 'Semua orang bicara tentang morning routine. Tapi sedikit yang konsisten melakukan weekly review — padahal inilah yang benar-benar menggerakkan jarum produktivitas jangka panjang.',
            'content' => "<p>Weekly review adalah praktik yang dipopulerkan oleh David Allen dalam metodologi Getting Things Done (GTD). Konsepnya sederhana: satu kali seminggu, Anda duduk dan meninjau semua yang sudah dilakukan, yang tertunda, dan yang perlu direncanakan ke depan.</p>\n\n<h2>Apa yang Dilakukan dalam Weekly Review?</h2>\n<p>Format weekly review yang efektif biasanya mencakup empat bagian:</p>\n<ol>\n<li><strong>Clear the inbox:</strong> Proses semua hal yang masuk selama seminggu — email, catatan, tugas yang baru muncul.</li>\n<li><strong>Review in progress:</strong> Cek semua todo dan jadwal yang masih aktif. Ada yang perlu diperbarui? Ditunda? Dihapus?</li>\n<li><strong>Reflect:</strong> Apa yang berhasil minggu ini? Apa yang tidak? Apa yang ingin Anda lakukan berbeda?</li>\n<li><strong>Plan ahead:</strong> Preview minggu depan. Adakah deadline penting? Event yang perlu dipersiapkan?</li>\n</ol>\n\n<h2>Berapa Lama Seharusnya?</h2>\n<p>Weekly review yang baik membutuhkan waktu 30–60 menit. Jika lebih dari itu, kemungkinan Anda menunda terlalu banyak hal hingga menumpuk. Jika kurang dari 15 menit, Anda mungkin melewatkan langkah penting.</p>\n\n<p>Jadwalkan weekly review di Schedule CrePlann setiap Jumat sore atau Minggu malam. Buat menjadi ritual yang menyenangkan: secangkir kopi, musik yang tepat, dan 45 menit tanpa gangguan.</p>",
        ],
        [
            'title'   => 'Inbox Zero: Strategi Mengelola Email agar Tidak Memenuhi Pikiran',
            'excerpt' => 'Inbox yang penuh bukan hanya masalah estetika — ini adalah beban kognitif yang terus-menerus menguras energi mental Anda sepanjang hari.',
            'content' => "<p>Inbox zero bukan berarti email Anda selalu kosong. Ini adalah filosofi bahwa inbox bukanlah tempat penyimpanan permanen — setiap email perlu diproses dan dipindahkan ke sistem yang tepat.</p>\n\n<h2>Empat Tindakan untuk Setiap Email</h2>\n<p>Setiap kali membuka email, pilih salah satu dari empat tindakan:</p>\n<ul>\n<li><strong>Delete/Archive:</strong> Jika tidak ada tindakan yang diperlukan dan informasinya tidak berguna.</li>\n<li><strong>Reply:</strong> Jika bisa dibalas dalam 2 menit atau kurang, balas sekarang.</li>\n<li><strong>Defer:</strong> Jika membutuhkan lebih dari 2 menit, jadwalkan waktu untuk mengerjakannya — catat sebagai todo di CrePlann.</li>\n<li><strong>Delegate:</strong> Jika bisa dikerjakan orang lain, teruskan dan tandai sebagai menunggu.</li>\n</ul>\n\n<h2>Mengintegrasikan Email dengan Sistem Produktivitas</h2>\n<p>Ketika email membutuhkan tindak lanjut, jangan biarkan ia duduk di inbox sebagai reminder. Buat todo di CrePlann dengan due date yang realistis, lalu archive emailnya. Inbox hanya untuk hal yang belum diproses — bukan sebagai sistem manajemen tugas.</p>",
        ],
        [
            'title'   => 'Belajar Mengatakan Tidak: Kunci Menjaga Fokus pada yang Penting',
            'excerpt' => 'Setiap kali Anda mengatakan ya pada sesuatu, Anda secara implisit mengatakan tidak pada hal lain. Belajar memilih dengan sadar adalah salah satu keterampilan produktivitas paling penting.',
            'content' => "<p>Warren Buffett pernah berkata: \"Perbedaan antara orang sukses dan orang sangat sukses adalah orang sangat sukses mengatakan tidak pada hampir semua hal.\" Pernyataan ini terdengar kontraintuitif — bukankah lebih banyak kesempatan berarti lebih banyak kemajuan?</p>\n\n<h2>The Paradox of Yes</h2>\n<p>Ketika Anda terus menerima permintaan, undangan, dan proyek baru tanpa selektif, Anda akhirnya menyebar energi ke terlalu banyak arah. Hasilnya: semua hal dikerjakan setengah-setengah, tidak ada yang benar-benar luar biasa.</p>\n\n<h2>Cara Mengevaluasi Permintaan</h2>\n<p>Sebelum menyetujui komitmen baru, tanyakan pada diri sendiri:</p>\n<ul>\n<li>Apakah ini selaras dengan prioritas utama saya minggu/bulan ini?</li>\n<li>Jika kalender saya sudah penuh, apakah saya masih ingin melakukan ini?</li>\n<li>Apakah ini menggerakkan saya menuju tujuan jangka panjang?</li>\n</ul>\n\n<p>Jika jawabannya tidak, tolak dengan sopan. \"Saya menghargai tawarannya, tapi saat ini saya perlu fokus pada komitmen yang sudah ada\" adalah respons yang profesional dan jujur.</p>\n\n<h2>Visualisasi di Weekly Grid</h2>\n<p>Salah satu manfaat tampilan weekly grid di CrePlann adalah Anda bisa langsung melihat seberapa padat minggu Anda sebelum menerima komitmen baru. Kalender yang visual membantu pengambilan keputusan yang lebih realistis.</p>",
        ],
        [
            'title'   => 'Pomodoro Technique: Bekerja dalam Sprint untuk Produktivitas Maksimal',
            'excerpt' => 'Otak manusia tidak dirancang untuk fokus selama berjam-jam tanpa henti. Teknik Pomodoro memanfaatkan ritme alami otak untuk memaksimalkan output tanpa mengorbankan energi.',
            'content' => "<p>Dikembangkan oleh Francesco Cirillo pada akhir 1980-an, Teknik Pomodoro adalah metode manajemen waktu yang membagi pekerjaan menjadi interval 25 menit (disebut \"pomodoro\") yang dipisahkan oleh istirahat pendek 5 menit.</p>\n\n<h2>Cara Kerja Teknik Pomodoro</h2>\n<ol>\n<li>Pilih tugas yang akan dikerjakan.</li>\n<li>Atur timer selama 25 menit.</li>\n<li>Kerjakan tugas tersebut dengan fokus penuh hingga timer berbunyi.</li>\n<li>Istirahat 5 menit — bergerak, minum air, jangan cek ponsel.</li>\n<li>Setelah 4 pomodoro, ambil istirahat lebih panjang: 15–30 menit.</li>\n</ol>\n\n<h2>Mengapa Ini Efektif?</h2>\n<p>Interval yang terbatas menciptakan urgensi artifisial yang membantu otak masuk ke mode fokus. Mengetahui bahwa istirahat sudah ada di cakrawala juga memudahkan untuk menahan diri dari distraksi — \"Hanya 20 menit lagi, saya bisa cek notifikasi setelah itu.\"</p>\n\n<h2>Integrasi dengan CrePlann</h2>\n<p>Gunakan Schedule untuk memblokir sesi pomodoro Anda. Buat entri jadwal berdurasi 2–3 jam untuk pekerjaan deep work, lalu dalam catatan tulis rencana berapa pomodoro yang akan dilakukan. Setelah sesi selesai, catat di Notes berapa pomodoro yang berhasil diselesaikan dan apa saja hambatan yang muncul.</p>",
        ],
        [
            'title'   => 'Mengelola Energi, Bukan Hanya Waktu: Pendekatan yang Lebih Manusiawi',
            'excerpt' => 'Manajemen waktu mengasumsikan semua jam adalah setara. Tapi jam 09.00 ketika Anda segar berbeda jauh dengan jam 15.00 setelah meeting panjang. Kelola energi, dan waktu akan mengikuti.',
            'content' => "<p>Tony Schwartz dan Jim Loehr dalam buku \"The Power of Full Engagement\" berpendapat bahwa fondasi produktivitas bukanlah waktu, melainkan energi. Waktu adalah sumber daya yang tetap — kita semua mendapat 24 jam. Tapi energi bisa dikelola, diperbaharui, dan dimaksimalkan.</p>\n\n<h2>Empat Dimensi Energi</h2>\n<p><strong>Fisik:</strong> Fondasi dari semuanya. Tidur cukup, olahraga teratur, dan nutrisi yang baik adalah investasi produktivitas, bukan kemewahan.</p>\n<p><strong>Emosional:</strong> Kemampuan untuk memilih respons emosional yang konstruktif. Stres yang tidak dikelola menguras energi kognitif.</p>\n<p><strong>Mental:</strong> Kemampuan untuk fokus, membuat keputusan, dan berpikir kreatif. Kapasitas ini terbatas dan berkurang sepanjang hari.</p>\n<p><strong>Spiritual/Purposeful:</strong> Terhubung dengan nilai dan tujuan yang lebih besar. Ini adalah sumber motivasi yang paling stabil.</p>\n\n<h2>Menerapkan Energy Management di CrePlann</h2>\n<p>Gunakan warna di Schedule untuk mencerminkan tipe energi yang dibutuhkan: merah untuk tugas high-focus, biru untuk meeting, hijau untuk tugas administratif ringan. Jadwalkan tugas terpenting di jam ketika energi Anda sedang puncak.</p>",
        ],
        [
            'title'   => 'Dari Overwhelm ke Clarity: Cara Memproses To-Do List yang Menggunung',
            'excerpt' => 'Saat to-do list tumbuh lebih cepat dari yang bisa diselesaikan, solusinya bukan bekerja lebih keras — tapi memproses dengan lebih cerdas.',
            'content' => "<p>Ada momen dalam hidup setiap pekerja produktif ketika daftar tugas terasa seperti gunung yang tidak bisa didaki. Email yang belum dibalas, proyek yang tertunda, janji yang belum ditepati — semuanya berdesakan dalam pikiran dan menciptakan perasaan overwhelm yang melumpuhkan.</p>\n\n<h2>Langkah Pertama: Keluarkan Semua dari Kepala</h2>\n<p>Buka Notes di CrePlann dan tuliskan semua hal yang ada di pikiran Anda — tanpa menyaring, tanpa menilai. Tugas besar, tugas kecil, hal yang mungkin tidak penting, semuanya keluar. Proses ini disebut \"mind sweep\" dan tujuannya adalah mengosongkan RAM mental Anda.</p>\n\n<h2>Proses dengan Pertanyaan Sederhana</h2>\n<p>Untuk setiap item dalam mind sweep, tanyakan:</p>\n<ul>\n<li><strong>Apakah ini actionable?</strong> Jika tidak, hapus atau arsipkan sebagai referensi.</li>\n<li><strong>Apakah bisa diselesaikan dalam 2 menit?</strong> Jika ya, kerjakan sekarang.</li>\n<li><strong>Apakah bisa didelegasikan?</strong> Jika ya, delegasikan dan tandai sebagai menunggu.</li>\n<li><strong>Kapan harus dilakukan?</strong> Tambahkan sebagai todo dengan due date di CrePlann.</li>\n</ul>\n\n<h2>Maintenance Mingguan</h2>\n<p>Jaga agar list tidak menggunung lagi dengan melakukan weekly review rutin. Hapus item yang tidak relevan, perbarui due date yang sudah lewat, dan pastikan setiap item masih worth doing.</p>",
        ],
    ];

    public function definition(): array
    {
        // Pilih topik secara acak dari daftar yang sudah dikurasi
        $topic = fake()->randomElement($this->productivityTopics);

        $publishedAt = fake()->dateTimeBetween('-6 months', 'now');

        return [
            'author_id'      => User::factory(),
            'title'          => $topic['title'],
            'slug'           => \Illuminate\Support\Str::slug($topic['title']).'-'.fake()->unique()->randomNumber(4),
            'excerpt'        => $topic['excerpt'],
            'content'        => $topic['content'],
            'featured_image' => null,
            'is_published'   => true,
            'published_at'   => $publishedAt,
            'views'          => fake()->numberBetween(10, 1200),
        ];
    }

    // ── States ───────────────────────────────────────────────────

    /** Post sudah dipublish */
    public function published(): static
    {
        return $this->state(fn () => [
            'is_published' => true,
            'published_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    /** Post masih draft */
    public function draft(): static
    {
        return $this->state(fn () => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    /** Post dengan author tertentu */
    public function forAuthor(User $user): static
    {
        return $this->state(fn () => ['author_id' => $user->id]);
    }
}
