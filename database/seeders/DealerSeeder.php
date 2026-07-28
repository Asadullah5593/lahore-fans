<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Lahore Fan's authorised dealers, compiled from the three source lists:
 *
 *   - lahore dealers.xlsx               (Lahore)
 *   - DEALERS LOCATIONS AND NUMBERS.xlsx (everywhere else)
 *   - NEW DEALER LIST.docx              (Karachi)
 *
 * Coordinates were derived from each dealer's own Google Maps share link: 107
 * of the 113 sit exactly where Google records the shop. The remaining six are
 * listed in APPROXIMATE_PINS below — Google publishes no coordinate for those,
 * so they sit on their town centre until someone corrects them.
 *
 * City names are the corrected spellings; the source files disagree on several
 * (KASOOR/Kasur, TEXTILA/Taxila, NANAKANA SHAB/Nankana Sahib, and others).
 *
 * Owner names come from the spreadsheets only — the Lahore and Karachi lists
 * have no owner column, so 46 of the 113 are null.
 *
 * Running this REPLACES every row in `stores`. Cities are matched by name and
 * created only when missing, so existing city rows keep their settings.
 */
class DealerSeeder extends Seeder
{
    /**
     * Pins Google exposes no coordinate for. These sit on their town centre and
     * should be dropped onto the exact shop with the map picker in
     * Admin -> Outlets -> Edit.
     */
    public const APPROXIMATE_PINS = [
        'AMJAD TRUCK & ELECTRONIC',  // Hafizabad
        'MUSLIM ELECTRIC STORE',  // Jhelum
        'KHAWAJA ELECTRIC STORE',  // Mandi Bahauddin
        'BAHOO ELECTRIC & ELECTRONICS',  // Okara
        'NADEEM ELECTRIC STORE',  // Sialkot
        'AL FALAH ELECTRIC STORE',  // Wah Cantt
    ];

    /** [city, name, owner, address, phone, second phone, lat, lng, maps url] */
    private const DEALERS = [
        ['Abbottabad', 'SHAD ELETRIC STORE', 'MR SAJJAD', 'MANDIAN ABBOTTABAD', '0321-9822029', null, 34.1971992, 73.2369125, 'https://maps.app.goo.gl/uDe5xp9hE6ENbsun8'],
        ['Arifwala', 'MAKKAH ELECTRIC STORE', 'MR MUNEEB ANJUM', 'QABOOLA BAZAR', '0457831164', null, 30.289776, 73.0680254, 'https://maps.app.goo.gl/rY1FFHSc3yWHGTem6'],
        ['Attock', 'HAROON ELECTRIC STORE', 'MR HAROON', 'BURQ ROAD', '0572602094', null, 33.7691625, 72.3544844, 'https://maps.app.goo.gl/HLinEwWoh1quKep69'],
        ['Bahawalpur', 'HAFIZ ELECTRIC STORE & FANCY LIGHT', 'HAFIZ MANZOOR', 'OPP. ISLAMIA CHECK POST AIRPORT ROAD', '0317-4645192', null, 29.3745012, 71.6933213, 'https://maps.app.goo.gl/twXNzDHAjnoyXUWF8'],
        ['Burewala', 'RAFAY ELECTRONIC', 'MUHAMMAD IMRAN', 'NEAR GHALMAN MUSTAFA MASJID VEHARI BAZAR', '0346-6060433', null, 30.1599875, 72.6741094, 'https://maps.app.goo.gl/6pLEwePq2db8cFCs6'],
        ['Chakwal', 'MAKKAH ELECTRIC STORE', 'HAJI JAVEED', 'PINDIBYPASS ROAD THANEEL PHATAK CHOWK', '0312-9966589', null, 32.9320488, 72.8578744, 'https://maps.app.goo.gl/o1kcfrTm9iEncPsq7'],
        ['Chichawatni', 'SAJID ELECTRIC AND SANITARY STORE', 'MR FAHID SAJID', 'Block Street Number 2 ROAD', '0345-7377201', null, 30.5372798, 72.6983215, 'https://maps.app.goo.gl/Jkn47Dsks7epMXym7'],
        ['Faisalabad', 'AHMAD ELECTRIC STORE', 'ALI ASGHAR', 'MEHAR SADIQ MARKET RAILWAY ROAD', '0412612800', null, 31.4165547, 73.0871398, 'https://maps.app.goo.gl/ScUXMTVFXg5i2PnG6'],
        ['Faisalabad', 'SAAD ELECTRIC', null, 'Kohinoor City Madina Town, near hockey stadium mian susan road', '0418724115', null, 31.417449, 73.113707, 'https://maps.app.goo.gl/1AEb8sLCQTnKDAcQ8'],
        ['Faisalabad', 'SHAMMAS TRADERS', 'MR ATIF', '3-J COMMERICAL MAIN BOULEVARD ROAD WAPDA CITY, CANAL ROAD', '0303-8556624', null, 31.4999273, 73.2188407, 'https://maps.app.goo.gl/DPZCrydCYCpeqgyJ8'],
        ['Faisalabad', 'ZOOM ELECTRIC Shop', 'KHALID MAHMOOD', 'Amin Town, canal road', '0300-9651247', null, 31.4409531, 73.1339454, 'https://maps.app.goo.gl/6ZahPN4ZA5hKfW2MA'],
        ['Faisalabad', 'ZUBAIR ELECTRIC STORE', 'M.AZEEM', 'SAMANABAD ROAD, BLOCK B SAMANABAD', '0321-9658017', null, 31.3883819, 73.0688161, 'https://maps.app.goo.gl/MEdWRmpga7yS16W2A'],
        ['Gujar Khan', 'LONDON ELECTRIC & LIGHT HOUSE', 'MR AFTAR', 'MAIN G.T ROAD. GUJAR KHAN', '', null, 33.2582873, 73.3026077, 'https://maps.app.goo.gl/LkS7R4BpAbL3p1Th8'],
        ['Gujranwala', 'BOMBAY LIGHT HOUSE', 'MR ARSHAD', 'HABIB PLAZA NEAR SHERANWALA BAGH G.T ROAD', '0300-6400127', null, 32.1549009, 74.190157, 'https://maps.app.goo.gl/RY2tdVYJ7KdmXL2V9'],
        ['Hafizabad', 'AMJAD TRUCK & ELECTRONIC', 'MR ZEESHAN', 'Railway Rd, opposite liberty market', '0332-4989424', null, 32.0712, 73.6885, 'https://maps.app.goo.gl/iwNKMNrp2xEyFKJJ9'],
        ['Haroonabad', 'SUPER ELECTRONIC', 'CH SADQAT', 'JAMIAN MASJID ROAD', '0300-8009293', null, 29.6107012, 73.1375537, 'https://maps.app.goo.gl/vdfNTs7TP1412Qrk7'],
        ['Hyderabad', 'EMAN TARDERS', 'MR AMEET KUMAR', 'House No F-185, near Allied School, Citizen Colony Qasimabad', '0334-2628163', null, 25.4114743, 68.3462631, 'https://maps.app.goo.gl/vrbv1Rr1k7Fdhkhr9'],
        ['Islamabad', 'abu bakar electric & fancy lights', 'abdul ghafoor', 'E-11, 4 St 12, Golra NPF E 11/4 E-11,', '0317-0572265', null, 33.6942364, 72.9869006, 'https://maps.app.goo.gl/Q96nptymUDrzoDmR9'],
        ['Islamabad', 'ikas trading', 'mr ajmad', 'I&T Centre، Block 91 Street 35, G-10/1 G 10/1 G-10', '0332-8054074', null, 33.668605, 73.0125327, 'https://maps.app.goo.gl/8pTxLJa3Hqj9MEog7'],
        ['Islamabad', 'makki electric store', 'arif mehmood', 'I & T Center, Block No. 47, Shop No:2, Peshawar Mor Interchange, G-9/4 G 9/4 G-9,', '0512255807', null, 33.6839418, 73.0415983, 'https://maps.app.goo.gl/5KxfefzWww6cUJvg6'],
        ['Islamabad', 'master electric & fancy light', 'mr mohsin', 'Gate # 2, Jan Plaza, GT Rd, opp. Dha2', '0515709258', null, 33.5237832, 73.1430864, 'https://maps.app.goo.gl/pgYapfUwSz2LYM188'],
        ['Islamabad', 'muhammadi machinery', 'tauseef ahmad', 'I & T Center, Block# 52, G-8/1 G 8/1 G-8,', '0512331812', null, 33.6899828, 73.053299, 'https://maps.app.goo.gl/dcN9ybKXZx5PpJ4J8'],
        ['Islamabad', 'new shalimar electric store', 'm. farhan iqbal', 'I&T Centre, Block 81 Street 14, G-8-1 G 8/1 G-8,', '0512335537', null, 33.6873741, 73.0482842, 'https://maps.app.goo.gl/3CW54xFLHxPRiG538'],
        ['Islamabad', 'pothar electric and general appliance', 'raja rashid', 'Nadir plaza, GT Rd, opposite Dha 2', '0333-5730633', null, 33.5225833, 73.1467446, 'https://maps.app.goo.gl/yRu1kEDji5Rx6kCVA'],
        ['Islamabad', 'qaim electric', 'MR IMRAN', 'G-12,', '0512360666', null, 33.6619375, 72.9920625, 'https://maps.app.goo.gl/HnDBYe4js7t3kVRA9'],
        ['Islamabad', 'zam zam electric store', 'sheikh ahmad mukhtair', 'Shop No 2, Nadir Plaza, G.T. Rd, opp. Lignum Tower, DHA Phase II', '0300-5554735', null, 33.5224591, 73.1472439, 'https://maps.app.goo.gl/R8Dz1uZhMKURTXri7'],
        ['Jaranwala', 'AL JANNAT MALL', 'MR JAWAD', 'Nia Bazar, near Bank Alfalah,', '0304-7888399', null, 31.3369895, 73.4187457, 'https://maps.app.goo.gl/5aBrhDNFPsbyrZg29'],
        ['Jhelum', 'MUSLIM ELECTRIC STORE', 'SHEHRYAR AMJAD DAR', 'SHOP NO 32,45 PRIME SQUARE RAILWAY ROAD', '0336-5921858', null, 32.9333, 73.7333, 'https://maps.app.goo.gl/PqFAVuwNHap7NNou8'],
        ['Karachi', 'A.S ELECTRO SOLAR', null, 'SHOP NO 4, MOHAN BUILDING, NEAR GURDWARA SAHIB, ARAMBAGH, KARACHI.', '0306-6699080', null, 24.8552999, 67.0140104, 'https://maps.app.goo.gl/WddyGN71pzAKD2et9'],
        ['Karachi', 'AHSAN & BROTHERS ELECTRIC CO.', null, 'UJALA ARCADE, ELECTRIC MARKET, NEAR GURDWARA SAHIB, ARAMBAGH, KARACHI.', '0333-2228754', null, 24.8554587, 67.0140303, 'https://maps.app.goo.gl/DoeuHFDKyVTsNvhx9'],
        ['Karachi', 'AL FAROOQ BUILD MART', null, 'PLOT NO 61, NEAR TAJ PETROL PUMP, M9 BAHRIA TOWN, KARACHI.', '0330-2266101', '0330-2266106', 24.9876117, 67.3020869, 'https://maps.app.goo.gl/HP8rusJF4wXp1Vux7'],
        ['Karachi', 'CARACHI ELECTRO GAS', null, 'SHOP NO 87, OPP K.M.C HEAD OFFICE, LIGHT HOUSE, M.A. JINNAH ROAD, KARACHI.', '0336-3535955', null, 24.8548875, 67.0078673, 'https://maps.app.goo.gl/3bdm76NCMhnKJ1fC6'],
        ['Karachi', 'DUA ELECTRIC', null, 'GHOUSIA ROAD, AGRA TAJ COLONY, KARACHI', '0320-0276990', '0332-3209142', 24.8707566, 66.9853331, 'https://maps.app.goo.gl/J8RNh2z4KKSYUk928'],
        ['Karachi', 'GOLDEN GAS', null, 'AL-KARIM CENTER, SECTOR 7, SECTOR NO 11-H, U.P MOR,NORTH KARACHI.', '0335-1229436', null, 24.9717423, 67.067022, 'https://maps.app.goo.gl/pXUjwp9Fi5ACkVAC6'],
        ['Karachi', 'GRAND ELECTRIC', null, 'SHOP NO 5, HARMAIN TOWER, GULISTAN E JAUHAR, JOHAR MOR, BLOCK 19, KARACHI.', '0332-7952434', null, 24.9045865, 67.1158101, 'https://maps.app.goo.gl/5XH2mqrChzQvYC3M8'],
        ['Karachi', 'HARMAIN ELECTRIC', null, 'JAM SADIQ ROAD, LOWER GIZRI, NEAR AKBER SANITARY, GIZRI, KARACHI.', '0335-2000638', null, 24.8222692, 67.0462073, 'https://maps.app.goo.gl/w2pDoHmb1YDZrTEa6'],
        ['Karachi', 'KHAN BROTHERS', null, 'MAIN SHAHRA E LIAQUAT, NEAR FRESCO SWEETS, BURNSROAD, ARAMBAGH,KARACHI.', '0315-8439538', null, 24.856142, 67.014548, 'https://maps.app.goo.gl/zeDP2Cw1399Jj1oe9'],
        ['Karachi', 'KHAN BROTHERS HYDERI', null, 'PLOT NO ST-04, AL BURHAN CIRCLE BLOCK E, NORTH NAZIMABAD, KARACHI.', '0312-1242863', null, 24.9369013, 67.0416337, 'https://maps.app.goo.gl/guvEVqTTLtqYTdea9'],
        ['Karachi', 'KSL ELECTRO', null, 'OPPOSITE K.M.C HEAD OFFICE, LIGHT HOUSE, MAIN M.A. JINNAH ROAD, KARACHI.', '0333-5565306', null, 24.8555366, 67.0076902, 'https://maps.app.goo.gl/qwhs71Udhcve4Hiy9'],
        ['Karachi', 'LAHORE FAN DHA GALAXY ENTERPRISES', null, 'SHOP NO 2, BUILDING NO 43-C, LANE NO 12, BUKHARI COMMERCIAL, PHASE 6, DHA, KARACHI.', '0345-8003382', null, 24.7910598, 67.0657906, 'https://maps.app.goo.gl/i8FdNYgZ2XuKsvS38'],
        ['Karachi', 'LAIBA ELECTRIC CO', null, 'SHOP NO 1 & 2, PLOT NO BS-2, BLOCK 14, WATER PUMP, NEAR KMC HEART HOSPITAL, KARACHI.', '0312-2624264', null, 24.9335119, 67.0789105, 'https://maps.app.goo.gl/KpJFiEd8vFPfmV9u9'],
        ['Karachi', 'M.R ELECTRIC', null, 'SHOP NO 82-83, SAVAKUNJ BUILDING, SHAHRA-E-LIAQUAT, NEAR ARAMBAGH MOSQUE, KARACHI.', '0331-2839773', null, 24.8550753, 67.0123931, 'https://maps.app.goo.gl/4Y2WZ9W54F2S53bC8'],
        ['Karachi', 'MASHAALLAH ELECTRIC', null, 'NEW HAJI CAMP, SULTANABAD, M.T. KHAN ROAD, NEAR SABQAH GHOUSIA MOSQUE, KARACHI.', '0345-8128250', '0345-3618798', 24.8419076, 67.01772, 'https://maps.app.goo.gl/wmzAWE3EsWDsNaNP8'],
        ['Karachi', 'MY ELECTRONICS', null, 'SHOP NO A83 & A84, RABIA PALACE, C-1, BLOCK 10A, MAIN RASHID MINHAS ROAD, GULSHAN-E-IQBAL, KARACHI.', '0333-4737271', '0321-8265070', 24.9088112, 67.1081673, 'https://maps.app.goo.gl/dCGNhmUUrWWfB3AH8'],
        ['Karachi', 'SMART WORLD ELECTRONIC', null, 'MANDVIWALA BUILDING, M.A. JINNAH ROAD, SERAI QUARTERS, DENSO HALL, KARACHI.', '0309-9990970', null, 24.8528444, 67.0043478, 'https://maps.app.goo.gl/YNj9WRwo1YnQgSKRA'],
        ['Karachi', 'TAWAKKAL ELECTRONICS', null, 'SHOP NO LS-4, MAIN BUS STOP NO 10, ORANGI TOWN, KARACHI.', '0301-2620725', null, 24.9402388, 66.993071, 'https://maps.app.goo.gl/u229kk2xZUc58wRX6'],
        ['Karachi', 'UNIVERSAL ELECTRICAL MART', null, 'MC-1306 B, GREEN TOWN, NEAR AZEEM PURA STOP, SHAH FAISAL, KARACHI.', '0300-2326782', null, 24.8758776, 67.1642508, 'https://maps.app.goo.gl/XghvBwspMXiS4bSp9'],
        ['Kasur', 'TAHIR ELECTRIC STORE', 'MR TAHIR RAIZ', 'OPP. DARBAR HAZRAT BABA BULLAY SHAH RAILWAY ROAD', '0321-6585150', null, 31.1201743, 74.4473616, 'https://maps.app.goo.gl/JDAJu8ZBfHkc4Lqq7'],
        ['Khanewal', 'NOOR SONS', 'SHEIKH AFTAB AHMAD', 'Akbar Bazar', '0652552205', null, 30.3002398, 71.9215061, 'https://maps.app.goo.gl/48qF55jgpjcCwuZJ9'],
        ['Lahore', 'al farooq electric store', null, 'Awan Town,', '0300-4394461', null, 31.5057907, 74.2695312, 'https://maps.app.goo.gl/n7sdvAFnkxXahpor9'],
        ['Lahore', 'AL JANNAT ELECTRONIC', null, 'Abid Market, 89b Temple Rd, Mozang Chungi', '', null, 31.5501511, 74.3155752, 'https://maps.app.goo.gl/TyGuCENN8hKzGCtS6'],
        ['Lahore', 'al makkah electric center', null, 'Shalimar, Shop # 1Al Riza Shopping Centre, Link Road, Mughalpura', '0321-9449737', null, 31.582068, 74.3810979, 'https://maps.app.goo.gl/ZdgVhKEYUjDmcVHS9'],
        ['Lahore', 'Al Muhafiz Sanitary, Electric & Hardware Store', null, 'Rail Town', '0333-4121166', null, 31.4373129, 74.2085558, 'https://maps.app.goo.gl/tv7FnsntCbYNnm847'],
        ['Lahore', 'Ali Hajvairy Electronics', null, '4 13 Al-Madina Road, Township Block 12 Sector B 1 B1', '0314-6776776', null, 31.4616798, 74.3096804, 'https://maps.app.goo.gl/fbfnNeWWL9vabNgV9'],
        ['Lahore', 'ASHIYANA LIGHTS', null, 'Beadon Rd, Garhi Shahu,', '04237356088', null, 31.562853, 74.320637, 'https://maps.app.goo.gl/oiAthMxJJBxJUCHDA'],
        ['Lahore', 'BISMILLAH TRADERS', null, '7a Beadon Rd, Victoria Park Jogi Mohalla Victoria Park', '0321-8496381', null, 31.5635372, 74.3217312, 'https://maps.app.goo.gl/jebZFRyqJmX4SNqYA'],
        ['Lahore', 'Gillani Electric and Sanitary', null, 'Alhamad chowk Silver state, College Road, Siddiqia Colony Block B PCSIR Staff Colony,', '0322-6971707', null, 31.4275912, 74.2865077, 'https://maps.app.goo.gl/FQV27m5DwHZ9bsiF6'],
        ['Lahore', 'Gillani Electric and sanitary DHA 5', null, 'Gillani Electric and Sanitary DHA 5 Jammu Stop, Bedian Rd Near Nawaz Sharif Interchange DHA Phase 5 Lahore 54792 Pakistan DHA PHASE 5, Sultan Colony,', '0311-4959234', null, 31.4697411, 74.4292139, 'https://maps.app.goo.gl/QEubKZMKWUpKM3FF6'],
        ['Lahore', 'hafiz electric store', null, 'New Shadbagh Rd, Shad Bagh', '04237281011', null, 31.5989375, 74.3384375, 'https://maps.app.goo.gl/4so593T3KRGy9UbA6'],
        ['Lahore', 'KHAWAJA ELECTRIC CORPORATION', null, '15, main Lahore – Kasur Rd, Jinnah Town', '04237422677', null, 31.5439315, 74.3172409, 'https://maps.app.goo.gl/9mSXLRLTCrksrbJV8'],
        ['Lahore', 'Lahore fans and Electronics', null, '238 B, Basement, Tulip Extension Commercial Area Sector C Bahria Town', '0320-0230932', null, 31.3679162, 74.1871149, 'https://maps.app.goo.gl/XpoKzFZyA2KB4krt5'],
        ['Lahore', 'Lahore Trading Center', null, 'Jammu Stop, Bedian Rd, near Nawaz Shareef Interchange, Sultan Colony', '0315-1466551', null, 31.4694596, 74.4295406, 'https://maps.app.goo.gl/raiBNwL8ecP7FB8Z6'],
        ['Lahore', 'LATIF ELECTRIC STORE', null, 'Beadon Rd, Garhi Shahu', '04237354285', null, 31.5629721, 74.3207184, 'https://maps.app.goo.gl/8Rm5rg6osT8UxbFa6'],
        ['Lahore', 'M.FAROOQ & CO', null, '2 Beadon Rd, Garhi Shahu', '0322-4240461', null, 31.5627439, 74.3205544, 'https://maps.app.goo.gl/2Y4DotXF83TzM6tH9'],
        ['Lahore', 'mashAllah traders', null, '192 B, Sector C Jasmine Block Sector C Bahria Town,', '0301-4523572', null, 31.3749084, 74.1876215, 'https://maps.app.goo.gl/9e4wbko4dH698yy77'],
        ['Lahore', 'master electric company', null, 'Sultan Colony', '0300-0470360', null, 31.4697721, 74.4291829, 'https://maps.app.goo.gl/HaLLnG4Fee7hwwvq8'],
        ['Lahore', 'Mehran Traders (Electrics, Paint & Sanitary Store)', null, 'Plot no 1, 1 Canal Bank Rd, near Izmir Towen, Block A Iqbal Avenue Phase 3 Iqbal Avenue - Phase 3,', '0322-4210511', null, 31.4292648, 74.1863927, 'https://maps.app.goo.gl/vGHDJA6aBV3fAkJj8'],
        ['Lahore', 'Mian Brother Electric House', null, 'Lahore-Jaranwala Rd, Shahdara Town', '0300-4340130', null, 31.6097772, 74.2570954, 'https://maps.app.goo.gl/CEwfTZ92adgccTV6A'],
        ['Lahore', 'NEW AHMAD ELECTRIC CO', null, '3 Beadon Rd, Garhi Shahu', '0344-9999696', null, 31.5636432, 74.3213218, 'https://maps.app.goo.gl/gnWvfG2acvYa3uRj7'],
        ['Lahore', 'NEW GENERAL ELECTRIC', null, '1 Beadon Rd, Garhi Shahu', '0312-4449223', null, 31.5631207, 74.3210487, 'https://maps.app.goo.gl/BuymYRanYdh24LQ56'],
        ['Lahore', 'NEW RAHAT ELECTRIC CO', null, 'Beadon Rd, Garhi Shahu', '0300-4134684', null, 31.5627858, 74.3205062, 'https://maps.app.goo.gl/WHvARdnHiLXRXpFK8'],
        ['Lahore', 'NIZAMI ELECTRIC CO', null, 'Beadon Rd, near chaman ice-cream, Garhi Shahu', '0306-4527098', null, 31.5630404, 74.3209633, 'https://maps.app.goo.gl/bGv4MCTDEfm2HUSR7'],
        ['Lahore', 'Pervaiz Electric Store', null, 'Walton Road, Koray Stop, opposite toyota cantt motors, Madina Colony', '0300-8866229', null, 31.4914662, 74.3704427, 'https://maps.app.goo.gl/7isBxzZ7tUxn3aYTA'],
        ['Lahore', 'Rauf & Sons Fans Wholesaler', null, '3-A Shahalam Market, Shah Alam Mohalla Chiri Maran Walled City of Lahore', '0300-9437609', null, 31.5767413, 74.317385, 'https://maps.app.goo.gl/rnXUyoSuuDwW5d9Z7'],
        ['Lahore', 'S.M electric co', null, 'Plot 27, Block 14, College Rd, Sector B-I, Township, Block 14 Sector B 1,', '04235150095', null, 31.4590086, 74.3033191, 'https://maps.app.goo.gl/wGrVGgBoUJ6gaKGh7'],
        ['Lawa', 'LAVA ELECTRONIC', 'MR IJAZ', 'MAIN BAAZAR', '0334-5566483', null, 32.6979355, 71.933234, 'https://maps.app.goo.gl/HADxmEbppu7cJrWm9'],
        ['Liaquatpur', 'BUTT ELECTRIC STORE', 'MR KHURRAM', 'Rest House Rd, Sheikh Mazher', '0301-8770977', null, 28.9335125, 70.9491719, 'https://maps.app.goo.gl/xqrp6kLcjWAxYgEA6'],
        ['Mandi Bahauddin', 'KHAWAJA ELECTRIC STORE', 'KHAWAJA NASIR', 'Old gts ada, Mandi Bahauddin', '0300-9609516', null, 32.5861, 73.4917, 'https://maps.app.goo.gl/H3CdyPURF5KRH2ds6'],
        ['Mardan', 'STAR LIGHT ELECTRONIC', 'MR NAVEED SHAH', 'Yousaf Shah Khan Plaza Shaheedan Bazar Rd New Mal Mandi, Judge Bazar, Mardan Khas', '0313-5190040', null, 34.1980159, 72.0507941, 'https://maps.app.goo.gl/SMDie93w5YZWzyn4A'],
        ['Mian Channu', 'DECENT TILES & SANITARY', 'MR MOMIN ZAHID', 'ILam Din Colony', '0333-1394000', null, 30.4455672, 72.3562826, 'https://maps.app.goo.gl/vEfvFeAsWwUB9Euy7'],
        ['Mirpur (AJK)', 'CHOHAN ENTERPRISES', 'MR MAFAR', 'Chohan Enterprises, 3 Kotli Rd, Sector F New Mirpur City', '0345-5565242', null, 33.1376247, 73.7738029, 'https://maps.app.goo.gl/7B6gyjg6rGFXJB9c8'],
        ['Multan', 'CHINA ELECTRIC STORE', 'MR ISHFAQ', 'Hassan Parwana Rd, Tipu Sultan Colony', '0300-6305955', null, 30.1917673, 71.4580713, 'https://maps.app.goo.gl/zrrcVHu7MxXh5JcR8'],
        ['Nankana Sahib', 'PAK ELECTRIC STORE', 'CH SAMI E ULLAH', 'Railway road Nearly Anarkali Bazar', '0312-4262713', null, 31.4518193, 73.7066282, 'https://maps.app.goo.gl/NaWevG4Grg35P4AfA'],
        ['Nowshera', 'EMIRATES ELECTRONIC', 'MR HADYATULLAH', 'ALI PLAZA, SADDAR BAZAR,', '0323610044', null, 34.004839, 71.9880367, 'https://maps.app.goo.gl/Naw7MUXKx4XcH2wM7'],
        ['Okara', 'BAHOO ELECTRIC & ELECTRONICS', 'MR SOHAIL', 'Block-C', '0300-6960049', null, 30.8081, 73.4458, 'https://maps.app.goo.gl/78EMYH1T1JMNHW2j9'],
        ['Pakpattan', 'NAWAZ ELECTRIC STORE', 'MR NAWAZ', 'Sahiwal Rd, opposite Admore petrol pump, Khan Pura', '0321-6940371', null, 30.3504411, 73.3782787, 'https://maps.app.goo.gl/a3VWvBFaNEmkkvhp6'],
        ['Pasrur', 'TARIQ ELECTRIC STORE', 'MR TARIQ', 'Purana Lari Adda Chowk,', '0321-7184636', null, 32.2640602, 74.6601921, 'https://maps.app.goo.gl/sGng7qLXUkJXJFs88'],
        ['Peshawar', 'SUNLIGHT ELECTRIC STORE', 'MR ZAKIR ULLAH', 'Canal Town', '0300-5696999', null, 33.9970627, 71.462963, 'https://maps.app.goo.gl/Tzyoeh992qACvZKn6'],
        ['Rahim Yar Khan', 'KEHKASHAN ELECTRIC STORE', 'HAJI SHAHID', '112-BANO ROAD', '0685873410', null, 28.4210198, 70.3054588, 'https://maps.app.goo.gl/g1onCVvHXWFfikq59'],
        ['Rawalpindi', 'ahmad traders, electric sanitary and paint store', 'mr nouman', 'Quaid Ave, Gulrez-2 Phase 2 Gulrez Housing Scheme', '0345-0548834', null, 33.561358, 73.1066283, 'https://maps.app.goo.gl/psdK29uKf3d8Gbf19'],
        ['Rawalpindi', 'awan electric sanitary kohinoor mill rwp', 'MR HAROON', 'Kohinoor mill, Naseerabad', '0333-5000510', null, 33.6195951, 72.98081, 'https://maps.app.goo.gl/AUVXcVwdxohgfGuU7'],
        ['Rawalpindi', 'ELECTROLINK TRADERS', 'MR UMAR', 'I-302, behind maharaja hotel, malik sher ahmad road, committee chowk, Committe Chowk', '0515132119', null, 33.6143239, 73.0639006, 'https://maps.app.goo.gl/obdhHWMZEPRG4sDV8'],
        ['Rawalpindi', 'imam electric', 'mr rashid', 'Bahria Expy', '0514917288', null, 33.5412029, 73.107715, 'https://maps.app.goo.gl/jjffDqGB1TMGdCeMA'],
        ['Rawalpindi', 'ishaq electric', 'mr sarmad iqbal', 'Pirzada Market, City-Sadar Road, opposite HABIB METRO BANK, Ibrahim Nagar', '0515536197', null, 33.6077066, 73.0542768, 'https://maps.app.goo.gl/trgMX1TKWwE1jLfU8'],
        ['Rawalpindi', 'minhas corporation', 'raja abrar nazir', 'Plaza #1280, Main PWD Rd, NPF Society Block A Police Foundation', '0515194159', null, 33.5673717, 73.1361754, 'https://maps.app.goo.gl/tmRcUmgyAXfh4vWQ7'],
        ['Rawalpindi', 'mir electronic', 'm. naeem mir', 'shop no, CA-259 Saidpur Rd, Satellite Town', '0518480579', null, 33.6443502, 73.0639986, 'https://maps.app.goo.gl/MHzYPrS1xx2CDrqw8'],
        ['Rawalpindi', 'modern electric', 'mr tariq', '331/324, Milad Chowk, Adam Jee Rd, Saddar', '0515125508', null, 33.599299, 73.053095, 'https://maps.app.goo.gl/6BHPN8wcsxogNwYL9'],
        ['Rawalpindi', 'nadeem electric store', 'naveed ur rehman', 'Babu Muhallah (New), Saddar', '0515511500', null, 33.5994385, 73.0533514, 'https://maps.app.goo.gl/m1wpQNPGPE9FAX4q7'],
        ['Rawalpindi', 'sabri cables house', 'mr nadeem', 'Shop H, 153 Iqbal Rd, Committee Chowk Mohalla Chitian Hatian Naya Mohalla', '0336-1800080', null, 33.6131442, 73.0638567, 'https://maps.app.goo.gl/AqtUi7UJckPxKg3e8'],
        ['Sadiqabad', 'NEW JUBLIE ELECTRIC STORE', 'MR MUNEER', 'HOSPITAL ROAD', '0300-8630547', null, 28.3085128, 70.1320445, 'https://maps.app.goo.gl/vSFkym6VjWmFohFU9'],
        ['Sahiwal', 'CROWN ELECTRIC STORE', 'MR MUSTAFA', 'Liaquat Chowk,', '0300-9692135', null, 30.667296, 73.1090079, 'https://maps.app.goo.gl/6s2EsQtUMYAeEh887'],
        ['Samundri', 'Jamat Ali Sanitary & Electric Store', 'M.SAUD', 'JAMAT ALI BAZAR', '0321-7934949', null, 31.0615326, 72.9600411, 'https://maps.app.goo.gl/JGvEzJ6NXK42MQ236'],
        ['Sangla Hill', 'E CENTER- ELECTRIC AND ELECTRONIC', 'UMAIR MIRZA', 'COMMITTEE BAZAR NEAR JAZZ FRANCHISE', '0302-7202331', null, 31.713617, 73.374466, 'https://maps.app.goo.gl/PhYcfy5yfeivmqZB6'],
        ['Sargodha', 'KHAN FANCY LIGHT HOUSE', 'MR IMRAN', 'kachehry bazar, 5 Block', '0300-9608806', null, 32.0845085, 72.6639102, 'https://maps.app.goo.gl/T7UUR25xKJ2JNtpX6'],
        ['Sheikhupura', 'AL KHALIL LIGHTS', 'MIAN KHALIL', 'Regal chok, Lahore Rd', '0300-4460057', null, 31.7113625, 73.9873906, 'https://maps.app.goo.gl/TUTvZeJvC89xPnku5'],
        ['Sialkot', 'NADEEM ELECTRIC STORE', 'SHEIKH A.REHMAN NADEEM', 'TRUNK BAZAR', '0336-4591393', null, 32.4945, 74.5229, 'https://maps.app.goo.gl/wz6uvmr3C8J4Utyy8'],
        ['Swat', 'SHAH RAWAN & SONS', 'FAZUL UR REHMAN', 'Madian Rd, Dolat Khel Market, Mohallah Khona Gul, Mingora', '0946817550', null, 34.7776959, 72.3587182, 'https://maps.app.goo.gl/BBG1QsZZDopvdPyy7'],
        ['Talagang', 'CITY ELECTRIC STORE', 'MALIK AMIR', 'Oppsite Eidgah Masjid, Rasheed Market, Mianwali - Talagang Rd, Muhammadabad', '0543411488', null, 32.9220011, 72.3991798, 'https://maps.app.goo.gl/2DtnfUs1bUahk4mr9'],
        ['Taxila', 'JANJUA TRADERS', 'MR AKRAM JANJUA', 'Faisal shaheed Road', '0322-5420765', null, 33.7399811, 72.8021774, 'https://maps.app.goo.gl/pSGqvqNTHH4FbocC9'],
        ['Toba Tek Singh', 'LALI ELECTRIC STORE', 'MR KHALID', 'Kashif Centre, Committee Rd, Machli Bazar', '0462515233', null, 30.9713413, 72.4794725, 'https://maps.app.goo.gl/X2Pn7wUkc6wiJVj49'],
        ['Vehari', 'AL KAREEM ELECTRIC STORE', 'MR NAEEM SHAHZAD', 'Club Rd, Christian Colony', '0303-6264015', null, 30.0449232, 72.3545291, 'https://maps.app.goo.gl/M3yy4hCMnPreVqHz6'],
        ['Wah Cantt', 'AL FALAH ELECTRIC STORE', 'MR SHAHID', 'lala rukh, Basti, near gol market,', '0300-5677083', '0334-5527336', 33.7742, 72.7106, 'https://maps.app.goo.gl/pSefrd2Sy4EDGZ86A'],
        ['Wah Cantt', 'madina electric & paint store', 'mr saad', 'Main Gt Road bahtar more', '0308-9033578', null, 33.7600056, 72.7328976, 'https://maps.app.goo.gl/TX92gBHH59mhLrZJ6'],
    ];

    public function run(): void
    {
        DB::transaction(function () {
            $removed = Store::query()->delete();

            if ($removed) {
                $this->command?->warn("Removed {$removed} existing outlet(s).");
            }

            // Reuse the cities already on record; add only the ones that are new.
            // A city with no centre of its own is centred on its dealers.
            $cityIds = [];

            foreach (self::DEALERS as [$city]) {
                $cityIds[$city] ??= City::firstOrCreate(['name' => $city])->id;
            }

            foreach (self::DEALERS as [$city, $name, $owner, $address, $phone, $phoneTwo, $lat, $lng, $url]) {
                Store::create([
                    'city_id' => $cityIds[$city],
                    'name' => $name,
                    'owner_name' => $owner,
                    'address' => $address,
                    'phone' => $phone,
                    'phone_two' => $phoneTwo,
                    'lat' => $lat,
                    'lng' => $lng,
                    'google_maps_url' => $url,
                    'is_active' => true,
                ]);
            }

            $this->command?->info(sprintf(
                'Seeded %d dealers across %d cities.',
                count(self::DEALERS),
                count($cityIds)
            ));
        });
    }
}
