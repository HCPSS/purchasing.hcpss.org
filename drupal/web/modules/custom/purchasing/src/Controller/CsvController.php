<?php

namespace Drupal\purchasing\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

class CsvController extends ControllerBase {

  /**
   * Download an example csv file.
   *
   * @param string $node_type
   */
  public function exampleFile($node_type) {
    switch ($node_type) {
      case 'vendor':
        $csv = $this->vendorCsv();
        break;
      case 'discounted_line_item':
        $csv = $this->discountedLineItemCsv();
        break;
      case 'priced_line_item':
        $csv = $this->pricedLineItemCsv();
        break;
      case 'award':
        $csv = $this->awardCsv();
        break;
      case 'solicitation':
        $csv = $this->solicitationCsv();
        break;
      default:
        throw new NotFoundHttpException();
    }

    $response = new Response($csv);
    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', "attachment; filename=\"{$node_type}.csv\"");

    return $response;
  }

  /**
   * Vendor example CSV.
   *
   * @return string
   */
  private function vendorCsv() {
    $csv = <<<'CSV'
name,address.line1,address.line2,address.city,address.state,address.zip,phone,fax,website,email,notes
Gopher Sport,2525 Lemond Street St. SW,,Owatonna,MN,55060,1-855-500-2749,1-888-319-7452,http://www.gophersport.com,jessestapp@gophersport .com,"Contact: Ms . Jesse Stapp
E-mail: jessestapp@gophersport .com"
School Specialty Inc.,140 Marble Drive,,Lancaster,PA,17601,888-388-3224,888-388-6344,http://www.schoolspecialty.com,orders@schoolspecialty .com,"Contact: Order Dept /Glenn Kennington (sales)
E-mail: orders@schoolspecialty .com"
"MFAC, LLC",1600 Division Rd,,West Warwick,RI,2893,1-800-556-7464 ext: 112,800-682-6950,http://www.performbetter.com,stevens@mfathletic.com,"Contact: Mr.Steven Strawderman
E-mail: stevens@mfathletic.com"
Pyramid School Products,6510 North 54th Street,,Tampa,FL,33610-1908,1-800-792-2644,813-621-7688,http://www.pyramidsp.com,order@pyramidsp.com,"Contact: Sales Dept.
E-mail : order@pyramidsp .com"
"S & S Worldwide, Inc.",75 Mill St.,,Colchester,CT,6415,800-642-7354 ext: 2361,800-566-6678,http://www.ssww.com,scervini@ssww.com,"Contact: Ms. Sandy Cervini
E-mail: scervini@ssww.com"
"BSN Sports, Passon's Sports, & US Games",P.O. Box 49,,Jenkintown,PA,19046,800-445-9446,800-523-5112,http://www.bsnsports.com,arhein@bsnsports.com ,"Contact: Mr. Adam Rhein
E-mail: arhein@bsnsports.com"
Glover,,,,,,,,,,
Douron,,,,,,,,,,
CSV;

    return $csv;
  }

  /**
   * Solicitation example CSV.
   *
   * @return string
   */
  private function solicitationCsv() {
    $csv = <<<'CSV'
title,bid_number,category,bids_due,effective.start,effective.end
Physical Ed Supplies,027.17.84,Instructional Supplies,4/30/18 14:00,5/1/18,5/1/19
Furniture,127.17.84,Furniture and Appliances,4/30/18 14:00,5/1/18,5/1/19
CSV;

    return $csv;
  }

  /**
   * Priced Line Item example CSV.
   *
   * return @string
   */
  private function pricedLineItemCsv() {
    $csv = <<<'CSV'
title,award,price,description,exceptions,install_charge
"Art Stool, Fixed Height - 18"" ",021.18.2.PC.01.8546,49.50,Model: Kreuger618 ,,
"Art Stool, Adj. 18-26"" ",021.18.2.PC.01.8546,57.10,Model: Kreuger 618A ,,
"Art Stool, Adj. 19-32"" ",021.18.2.PC.01.8546,76.25,Model: Royal Seating Allegiance #627024N (#1500) ,,
"Bench, Wood with Arms, Small (48"") ",021.18.2.PC.01.8546,1394.50,Model: Community 991A ,,
"Bench, Wood with Arms, Large (68"") ",021.18.2.PC.01.8546,1558.60,Model: Community 993A ,,
"Book Cart, double-sided, 6-shelf (36x18x43H) ",021.18.2.PC.01.8546,355.90,Model: Smith System 21001 ,,
"Book Cart, single-sided, 3-shelf ",021.18.2.PC.01.8546,292.00,Model: Smith System 21092 ,,
"Bookcase, metal, 3-shelf, 42"" (42x12x36) ",021.18.2.PC.01.8546,133.00,Model: Lee Metal BR42C (BA20) ,,
"Bookcase, metal, 4-shelf, 52"" (52x12x36) ",021.18.2.PC.01.8546,156.00,Model: Lee Metal BR52C (BA30) ,,
"Bookcase, metal, Z shelf (29"" x 12"" x 36"") ",021.18.2.PC.01.8546,129.00,Model: Lee Metal BR29 (BA10) ,,
"Bulletin Board, 4 x 4 ",021.18.2.PC.01.8546,174.50,Model: Claridge 844N ,,30
"Bulletin Board, 4 x 5 ",021.18.2.PC.01.8546,203.05,Model: Claridge 959A ,,30
"Bulletin Board, 4 x 6 ",021.18.2.PC.01.8546,215.90,Model: Claridge 852N ,,40
"Bulletin Board, 4 x 8 ",021.18.2.PC.01.8546,265.75,Model: Claridge 858N ,,40
"Bulletin Board, 4 x 10 ",021.18.2.PC.01.8546,304.50,Model: Claridge 862N ,,45
"Bulletin Board, 4 x 12 ",021.18.2.PC.01.8546,337.80,Model: Claridge 864N ,,50
"Bulletin Board, 4 x 16 ",021.18.2.PC.01.8546,393.85,Model: Claridge 866N ,,50
"Bulletin Board, Portable, magnetic, Green, 4 x 6 ",021.18.2.PC.01.8546,812.00,Model: Claridge Vitracite #60V w/casters add one ,,80
"Bulletin Board, Portable, magnetic, Black, 4 x 6 ",021.18.2.PC.01.8546,812.00,Model: Claridge Vitracite #62V w/casters add one ,,40
"Bulletin Board, Portable, magnetic, Green, 42"" x 60"" ",021.18.2.PC.01.8546,724.00,Model: Claridge Vitracite #61 V w/casters add one ,,40
"Bulletin Board, Portable, magnetic, Black, 42"" x 60"" ",021.18.2.PC.01.8546,724.00,Model: Claridge Vitracite #63V w/casters add one ,,40
"Bulletin Board, Portable, magnetic, Green, 3 x 4' ",021.18.2.PC.01.8546,609.00,Model: Claridge Vitracite #50V w/casters add one ,,30
"Bulletin Board, Portable, magnetic, Black, 3 x 4' ",021.18.2.PC.01.8546,609.00,Model: Claridge Vitracite #52V w/casters add one ,,30
"Cabinet, Storage, Combination (1/2 storage, 1/2 cabinet) ",021.18.2.PC.01.8546,334.00,Model: Lee Metal 8118W (CAC1) ,,
"Cabinet, Storage, w/lock, 72x36x18 ",021.18.2.PC.01.8546,295.00,Model: Lee Metal 8036C (CA 41) ,,
"Cabinet, Storage, w/lock, 78x36x24D ",021.18.2.PC.01.8546,384.00,Model: Lee Metal 8208C (CA 41) ,,
"Carrell, with adj. Legs (36x24x22-29""H) ",021.18.2.PC.01.8546,310.70,Model: Arico Bell 1321 ,,
"Chair, Folding Tablet, right handed folding tablet, metal chair ",021.18.2.PC.01.8546,116.90,Krueger 701 FTAR ,,
"Chair, Folding ",021.18.2.PC.01.8546,41.60,Krueger701 ,,
"Chair Truck, holds 50 folding chairs ",021.18.2.PC.01.8546,391.60,Krueger KV50 - Beige ,,
"Chair Truck, double-tier, holds 84 chairs ",021.18.2.PC.01.8546,999.80,Krueger KDT84 - Beige ,,
"Chair, Folding, metal, beige (52-Gray) ",021.18.2.PC.01.8546,15.95,Model: National Public Seating #51 ,,
"Chair, Folding, metal, beige (52-Gray) PRICE FOR 500+ ",021.18.2.PC.01.8546,15.95,Model: National Public Seating #51 ,,
"Folding Chair Caddy, holds 84 chairs ",021.18.2.PC.01.8546,242.80,Model: National Public Seating #84 ,,
"Chair, Music, 16-1/2"", stackable ",021.18.2.PC.01.8546,49.10,Model: Columbia 1286 ,,
"Chair, Music, 17-1/2"", stackable ",021.18.2.PC.01.8546,50.10,Model: Columbia 1287 ,,
"Chair, Music, 18-1 /2"", stackable ",021.18.2.PC.01.8546,50.60,Model: Columbia 1289 ,,
"Chair Handtruck for Music Chairs, stacks up to 10 chairs ",021.18.2.PC.01.8546,83.25,Model: Columbia 0010X ,,
"Chair, Student, 11-1/2"", Soft Plastic ",021.18.2.PC.01.8546,23.10,Model: Arico Bell 7101 ,,
"Chair, Student, 13-1/2"" "" ",021.18.2.PC.01.8546,23.10,Model: Arico Bell 7103 ,,
"Chair, Student, 15-1/2"" "" ",021.18.2.PC.01.8546,27.60,Model: Arico Bell 7105 ,,
"Chair, Student, 17-1/2"" "" ",021.18.2.PC.01.8546,29.75,Model: Arico Bell 7107 ,,
"Chair, Student, 18-1/2"" (Oversize), Soft Plastic ",021.18.2.PC.01.8546,34.10,Model: Arico Bell 7108 ,,
"Chair Truck for Stackable Chairs, 4-wheel, holds 10-15 chairs ",021.18.2.PC.01.8546,68.30,Model: Arico Bell 0168 ,,
"Chair Truck for Stackable Chairs, 2-wheel, holds 1 0 chairs (tilt-back) Arcto Bell 0160 ",021.18.2.PC.01.8546,87.70,Model: Arcto Bell 0160 ,,
"Chair, Student, 13-1/2"", Soft Plastic, Sled Base ",021.18.2.PC.01.8546,29.75,Model: Arico Bell 7003 ,,
"Chair, Student, 15-1/2"" "" "" ",021.18.2.PC.01.8546,33.60,Model: Arico Bell 7005 ,,
"Chair, Student, 17-1 /2"" "" "" ",021.18.2.PC.01.8546,35.50,Model: Arico Bell 7007 ,,
"Chair, Student, 12"", Soft Plastic ",021.18.2.PC.01.8546,22.80,Model: Arico Bell D1 OD (Discover) ,,
"Chair, Student, 14"", Soft Plastic ",021.18.2.PC.01.8546,22.80,Model: Arico Bell D1 0C (Discover) ,,
"Chair, Student, 16, Soft Plastic ",021.18.2.PC.01.8546,26.75,Model: Arico Bell D1 OB (Discover) ,,
"Chair, Student, 18"", Soft Plastic ",021.18.2.PC.01.8546,29.90,Model: Arico Bell D1 0A (Discover) ,,
"Chair, Student, 18""A+, Soft Plastic ",021.18.2.PC.01.8546,34.30,Model: Arico Bell D1 OX (Discover) ,,
"Chair, Student (Extra Large), Soft Plastic ",021.18.2.PC.01.8546,41.60,Model: Columbia 1268 ,,
"Chair, Student, 13"", Soft Plastic, IQ (Ergonomic) ",021.18.2.PC.01.8546,35.70,Model: Columbia 1263 ERGO ,,
"Chair, Student, 15"", "" "" "" ",021.18.2.PC.01.8546,36.25,Model: Columbia 1265 ERGO .. ,,
"Chair, Student, 17-1/2"" "" "" ",021.18.2.PC.01.8546,38.10,Model: Columbia 1267 ERGO ,,
"Chair, Student, 12"", Hard Plastic ",021.18.2.PC.01.8546,53.50,Model: Columbia 112-2 ,,
"Chair, Student, 13-1/2"", "" ",021.18.2.PC.01.8546,54.40,Model: Columbia 112-3 ,,
"Chair, Student, 15-1/2"", "" ",021.18.2.PC.01.8546,55.00,Model: Columbia 112-5 ,,
"Chair, Student, 17-1 /2"", "" ",021.18.2.PC.01.8546,56.20,Model: Columbia 112-7 ,,
"Chair, Student, Hard Plastic, Adjustable legs ",021.18.2.PC.01.8546,59.15,Model: Columbia 113 ,,
"Chair, Student, 12"", Hard Plastic, with ""X"" Brace ",021.18.2.PC.01.8546,54.85,Model: Columbia 152-2 ,,
"Chair, Student, 13-1/2"", "" "" ",021.18.2.PC.01.8546,55.25,Model: Columbia 152-3 ,,
"Chair, Student, 15-1/2"", "" "" ",021.18.2.PC.01.8546,56.10,Model: Columbia 152-5 ,,
"Chair, Student, 17-1/2"", "" "" ",021.18.2.PC.01.8546,57.40,Model: Columbia 152-7 ,,
"Chair, Student, 17-1/2, Hard Plastic w/bookrack & casters (computer lab) ",021.18.2.PC.01.8546,78.90,Model: Columbia 2327 ,,
"Chair, Student, 12"", Soft Plastic ",021.18.2.PC.01.8546,35.00,Model: Columbia 126-2 ,,
"Chair, Teacher, 17-1/2 with upholstery and NO arms ",021.18.2.PC.01.8546,67.90,Model: Columbia 426-7 ,,
"Chair, Teacher, Soft Plastic with pads, NO arms ",021.18.2.PC.01.8546,103.50,Model: Arico Bell 7167 ,,
"Chair, Teacher, "" "", with arms, pads & casters ",021.18.2.PC.01.8546,129.95,Model: Arico Bell 7167 ,,
"Chair, Teacher, Task Style, w/o arms ",021.18.2.PC.01.8546,168.10,Model: Hon 7901 ,,
"Chair, Teacher, Task Style, with arms (either 7991 or 7992 arms) ",021.18.2.PC.01.8546,182.20,Model: Hon 7901 ,,
"Chair, Teacher, Task Style, w/o arms ",021.18.2.PC.01.8546,168.50,Model: Global 2312 ,,
"Chair, Teacher, Task Style, w/ loop arms ",021.18.2.PC.01.8546,200.20,Model: Global 2313 ,,
"Chair, Teacher, Task Style, w/o arms . ",021.18.2.PC.01.8546,174.30,Model: United NS-1 ,,
"Chair, Teacher, with arms ",021.18.2.PC.01.8546,191.20,Model: United NS-2 ,,
"Chalkboard, 3 x 5 Non-maonetic ",021.18.2.PC.01.8546,173.00,Model: Best-Rite 101AE ,,30
"Chalkboard, 4 x 4 , Non-magnetic ",021.18.2.PC.01.8546,194.00,Model: Best-Rite 101AD ,,30
"Chalkboard, 4 x 5 Non-magnetic ",021.18.2.PC.01.8546,209.00,Model: Best-Rite 101AF ,,30
"Chalkboard, 4 x 6 Non-magnatic ",021.18.2.PC.01.8546,246.00,Model: Best-Rite 101AG ,,30
"Chalkboard, 4 x 8 Non-magnetic ",021.18.2.PC.01.8546,348.00,Model: Best-Rite 101AH ,,40
"Chalkboard, 4 x 10 Non-magntic ",021.18.2.PC.01.8546,422.00,Model: Best-Rite 101AK ,,45
"Chalkboard, 4 x 12 Non-magnetic ",021.18.2.PC.01.8546,491.00,Model: Best-Rite 101AM ,,50
"Chalkboard, 42"" x 96"", Magnetic ",021.18.2.PC.01.8546,535.00,Model: Best-Rite 104AH-CUT ,,40
"Chalkboard, 4 x 4 , Magnetic (Green standard; Specify #13 Black for black) ",021.18.2.PC.01.8546,328.30,Model: Claridge 818V ,,30
"Chalkboard, 4 x 6, Magnetic ",021.18.2.PC.01.8546,436.00,Model: Claridge 822V ,,40
"Chalkboard, 4 x 8 , Magnetic ",021.18.2.PC.01.8546,488.75,Model: Claridge 826V ,,40
"Chalkboard, 4 x 8 , Magnetic ",021.18.2.PC.01.8546,468.00,Model: BR104AH ,,40
"Chalkboard, 4 x 10, Magnetic ",021.18.2.PC.01.8546,641.90,Model: Claridge 830V ,,45
"Chalkboard, 4 x 12, Magnetic ",021.18.2.PC.01.8546,718.00,Model: Claridge 834V ,,50
"Chalkboard, 4 x 16, Magnetic ",021.18.2.PC.01.8546,882.00,Model: Claridge 838V ,,50
"Chalkboard cork strips, 1"" wide, price per linear foot ",021.18.2.PC.01.8546,4.15,Model: Claridge 51 ,,
"Chalkboard cork strips, 2"" wide, "" "" "" "" ",021.18.2.PC.01.8546,6.90,Model: Claridge74 ,,
Cork Strip End Stop (for above) 51 ES (,021.18.2.PC.01.8546,2.10,Model: Claridge) ,,
"Chalkboard, portable, wood frame, 4 x 6' ",021.18.2.PC.01.8546,815.00,Model: Claridge D2GV + 2 casters ,,
"Chalkboard, portable, aluminum frame, 4 x 6' ",021.18.2.PC.01.8546,810.00,Model: Claridge 60V + 2 casters ,,
Computer Table,021.18.2.PC.01.8546,233.90,Bait WOW-TR ,,
"Computer Table, 30 x 60, adj. Legs ",021.18.2.PC.01.8546,431.20,Model: Arico Bell ABCD60 ,,
Wire Management Tray for above ,021.18.2.PC.01.8546,32.80,Model: WMT-5 ,,
CPU Holder for above ,021.18.2.PC.01.8546,82.15,Model: CPU-1 ,,
"Computer Table, 30x60 ",021.18.2.PC.01.8546,205.10,,,
"Computer Table, 30x72 ",021.18.2.PC.01.8546,222.25,,,
Keyboard platform for above ,021.18.2.PC.01.8546,146.50,,,
"4"" Heavy Duty Casters ",021.18.2.PC.01.8546,32.50,,,
"Desk, Student, No Box, laminate top, adj. legs ",021.18.2.PC.01.8546,52.40,Model: Arico Bell 91 00 ,,
"Desk, Student, Bookbox, laminate top, adj. Legs ",021.18.2.PC.01.8546,56.75,Model: Arico Bell 9500 ,,
"Desk, Student, Bookbox, adj. Legs ",021.18.2.PC.01.8546,51.20,Model: Artco Bell DA0D (Discover) ,,
"Desk, Student, Handicpped (20"" x 36"") ",021.18.2.PC.01.8546,86.50,Model: Arico Bell 2900 ,,
"Desk, Student, Oversize (20"" x 26""), No Bookbox ",021.18.2.PC.01.8546,63.10,Model: Artco Bell 9106 ,,
"Desk, Student, Hard Plastic Top, No Book Box, Adjust. Leg ",021.18.2.PC.01.8546,72.80,Model: Columbia 711 ,,
"Desk, Student, Hard Plastic Top, with Book Box, Fixed Legs, 22-1/2"" ",021.18.2.PC.01.8546,78.40,Model: Columbia 311-2 ,,
"Desk, Student, "" "" "" "" "" "" "" "" 23-1/2"" ",021.18.2.PC.01.8546,78.40,Model: Columbia 311-3 ,,
"Desk, Student, "" "" "" "" "" "" "" "" 26-1/2"" ",021.18.2.PC.01.8546,79.20,Model: Columbia 311-6 ,,
"Desk, Student, "" "" "" "" "" "" "" "" 29-1/2"" ",021.18.2.PC.01.8546,80.50,Model: Columbia 311-9 ,,
"Desk, Student, Hard Plastic Top & Book Box, Adj. Legs, 22-30"" ",021.18.2.PC.01.8546,82.65,Model: Columbia 313 ,,
"Desk, Student, Hard Plastic Top & Wire Basket w/specflec coated legs ",021.18.2.PC.01.8546,91.55,Model: Columbia 377 ,,
"Desk, Student, Hard Plastic Top & Wire Basket w/chrome legs ",021.18.2.PC.01.8546,92.10,Model: Columbia 377 ,,
"Desk, Student, Hard Plastic Top, NO BASKET, w/coated legs ",021.18.2.PC.01.8546,71.25,Model: Columbia 711-9 ,,
"Desk, Student, Hard Plastic Top, Wheelchair accessible, 32"" fixed of adj, ht. ",021.18.2.PC.01.8546,90.10,Model: Columbia 5060 ,,
"Desk, with Tablet Arm with Bookrack ",021.18.2.PC.01.8546,112.75,Model: Columbia 8727 ,,
"Desk, Tablet Arm (movable), Miracle Fold (Beiae or Grav) ",021.18.2.PC.01.8546,133.10,Model: Kl 7015TAR (701 FTAR) ,,
"Desk, Teacher, 30 x 48, Single Pedestal & Center Drawer ",021.18.2.PC.01.8546,428.60,Model: Adelphia 48S with center drawer ,,
"Desk, Teacher, 30 x 60, Double Pedestal & Center Drawer ",021.18.2.PC.01.8546,539.50,Model: Adelphia 3060DP/CD ,,
"Dry Erase Board, Magnetic, 4 x 8 (includes installation) ",021.18.2.PC.01.8546,492.00,Model: Claridge LCS2048 ,,
"File Cabinet, 2-drawer vertical, letter w lock, 28-1/2"" D ",021.18.2.PC.01.8546,227.00,Model: Adelphia 402L . ,,
"File Cabinet, 4-drawervertcal, letterw/lock, 28-1/2"" D ",021.18.2.PC.01.8546,297.00,Model: Adelphia 404L ,,
"File Cabinet, 5-drawer vertical, letter, w/lock, 28-1/2"" D ",021.18.2.PC.01.8546,350.00,Model: Adelphia 405L ,,
"File Cabinet, 4-drawer vertical, LEGAL, with lock ",021.18.2.PC.01.8546,333.00,Model: Adelphia 414L ,,
"File Cabinet, 4-drawer lateral with drawers, letter ",021.18.2.PC.01.8546,529.00,Model: Anderson Hickey L442 (Global 9342-4F1 H) ,,
"Flat File (Poster), 49W x 29D x 36H, 5-Drawer ",021.18.2.PC.01.8546,760.00,Model: Lee Metal 9558 ,,
Rocking Chair Holbrook  ,021.18.2.PC.01.8546,293.90,HT08671 ,,
"Stool, Adjustable 18-27""",021.18.2.PC.01.8546,57.10,Model: Kl618A ,,
"Stool, Adjustable 18-27""",021.18.2.PC.01.8546,39.00,Model: National Public Seating 6224H ,,
"Stool, Fixed height of 24""",021.18.2.PC.01.8546,29.00,Model: National Public Seating 6224 ,,
"Stool, Fixed height of 30""  ",021.18.2.PC.01.8546,36.00,Model: National Public Seating 6230 ,,
"Table, Art, Student Pedestal, 42 x 60   ",021.18.2.PC.01.8546,735.00,Model: Shain Shop Bill PT61 M ,,
"Table, Cafeteria (Bench Style), 12' Benches",021.18.2.PC.01.8546,1261.30,Model: Palmer Snyder Model #63T09293012 ,,
"Table, Cafeteria, Stool, 12'",021.18.2.PC.01.8546,1440.00,Model: Palmer Snyder 59M293012-S12 (1-9 tables) ,,
"Table, Cafeteria - 60"" Round or Octagon, folding, on casters    ",021.18.2.PC.01.8546,633.25,Model: Palmer Snyder 22F29600C ,,
"Table, Cafeteria, 12' with Benches  ",021.18.2.PC.01.8546,1495.00,Model: Sico TBC 13F ,,
"Table, Cafeteria, 12' with Benches ",021.18.2.PC.01.8546,1475.00,Model: BioFit 12FB29 ,,
"Table, Folding, Lightweight, Plastic, Beige/Dark Oak, 30 x 96""  ",021.18.2.PC.01.8546,358.45,Model: Palmer Snyder 83096SW ,,
"Table, Round, with 4 book boxes ",021.18.2.PC.01.8546,346.60,Model: Allied F560CR & 4881 ,,
"Table, Science, 48 x 24"" ",021.18.2.PC.01.8546,285.00,Model: Chemsurf (Black) Top - Top Only ,,
"Table, Science, 24 x 54"", wood base with Chemsurf resistant laminate top BS2454A (35 for 2 book compartments) ",021.18.2.PC.01.8546,440.70,Model: Allied) ,,
"Table, Science, 30 x 60, "" "" (+ ""BB"" &add ",021.18.2.PC.01.8546,463.25,Model: BS3060A ,,
"Table, Student Activity, 24 x 48, adj. Legs ",021.18.2.PC.01.8546,95.00,Model: Allied F52448 ,,
"Table, Student Activity, 30 x 60, adj. Legs ",021.18.2.PC.01.8546,122.00,Model: Allied F53060 ,,
"Table, Student Activity, 30 x 60, adj. Legs, Gray top & color sides ",021.18.2.PC.01.8546,122.00,Model: Allied F63060 ,,
"Table, Student Activity, 36 x 60, adj. Legs ",021.18.2.PC.01.8546,137.00,Model: Allied F53660 . ,,
"Table, Student Activity, 30 x 72, adj Legs ",021.18.2.PC.01.8546,137.00,Model: Allied F53072 ,,
"Table, Student Activity, 36 x 72, adj. Legs ",021.18.2.PC.01.8546,139.00,Model: Allied F53672 ,,
"Table, Student Activity, 36 x 96, adj. Legs ",021.18.2.PC.01.8546,225.00,Model: Allied F53696 ,,
"Table, Student Activity, 36"" Round ",021.18.2.PC.01.8546,115.00,Model: Allied F536CR ,,
"Table, Student Activity, 42"" Round ",021.18.2.PC.01.8546,118.00,Model: Allied F542CR ,,
"Table, Student Activity, 48"" Round ",021.18.2.PC.01.8546,145.00,Model: Allied F548CR ,,
"Table, Student Activity, Octagon, 48"" ",021.18.2.PC.01.8546,149.00,Model: Allied F5480T ,,
"Table, Folding, 18 x 60"" (Laminates: Walnut/LI. Oak/Teak/Birch) ",021.18.2.PC.01.8546,109.00,Model: Allied 121860 ,,
"Table, Folding, 30 x 72"" ",021.18.2.PC.01.8546,129.00,Model: Allied 123072 ,,
"Table, Kidney ",021.18.2.PC.01.8546,232.70,Model: Allied F5472 ,,
"Table, Horseshoe, 60 x 66, adj. Legs ",021.18.2.PC.01.8546,269.00,Model: Allied F5666H ,,
"Table, Trapezoid, 30x30x60 ",021.18.2.PC.01.8546,131.20,Model: Allied F530TP ,,
"Whiteboard, 2 x 3, Magnetic ",021.18.2.PC.01.8546,113.90,Model: Claridge LCS2023 ,,30
"Whiteboard, 3 x 4, Magnetic ",021.18.2.PC.01.8546,281.05,Model: Claridge 814L ,,30
"Whiteboard, 4 x 4, Maanetic ",021.18.2.PC.01.8546,343.10,ClaridQe 818L ,,30
"Whiteboard, 4 x 5, Magnetic ",021.18.2.PC.01.8546,419.00,Model: Claridge 822L Modified ,,40
"Whiteboard, 4 x 6, Magnetic ",021.18.2.PC.01.8546,419.00,Model: Claridge 822L ,,40
"Whiteboard, 4 x 8, Magnetic ",021.18.2.PC.01.8546,495.00,Model: Claridge 826L ,,40
"Whiteboard, 4 x 8 White melamine, NOT magnetic, ",021.18.2.PC.01.8546,371.20,Model: Claridge MLC2048R ,,40
"Whiteboard, 4 x 6, on casters, magnetized, reversible board (Add 1) ",021.18.2.PC.01.8546,783.00,Model: Claridge LCS56 with casters (LCS59) ,,
"Whiteboard, 4 x 6, on casters, both sides magnetized , chalk & whiteboard ",021.18.2.PC.01.8546,996.25,Model: Claridge LCS56 (modified) (LCS559) ,,
"Whiteboard, 4 x 6, on casters, magnetized, with Music Lines ",021.18.2.PC.01.8546,1081.90,Model: Claridge LCS56 with Casters & Music Lines ,,
"Whiteboard, 4 x 6, on casters, NOT magnetized ",021.18.2.PC.01.8546,615.00,Model: Claridge MLC56 with casters add one ,,
"Whneboard, 4 x 8, without ledge (for Gym), Silver Frame ",021.18.2.PC.01.8546,363.70,Model: Best-Rite 2028H UltraTrim with Silver Frame ,,40
"Whiteboard, 4 x 8, without ledge (for Gym), Black Frame ",021.18.2.PC.01.8546,363.70,Model: Best-Rite 2029H UltraTrim with Black Frame ,,40
"Whiteboard, 4 x 10, magnetic with alum frame ",021.18.2.PC.01.8546,637.60,LCS 2410 ,,50
"Whiteboard, 4 x 10, magnetic with alum frame ",021.18.2.PC.01.8546,615.00,Model: Claridge 830L ,,50
"Whiteboard, 4 x 12, magnetic with alum frame ",021.18.2.PC.01.8546,718.00,Model: Claridge 834L ,,50
"Whiteboard, 4' x 10', Non-Magnetic ",021.18.2.PC.01.8546,402.90,Model: Best-Rite 212AK ,,50
"Whiteboard, 4' x 12', Non-Magnetic ",021.18.2.PC.01.8546,469.00,Model: Best-Rite 212AM ,,50
"Whiteboard, 4' x 16', Magnetic ",021.18.2.PC.01.8546,896.25,Model: Best-Rite 202AP ,,50
"Whiteboard, 4' x 16', Non-Magnetic ",021.18.2.PC.01.8546,713.60,Model: Best-Rite 212AP ,,50
"Whiteboard/Bulletin Board, 4 x 10, with alumunum frame ",021.18.2.PC.01.8546,623.90,Model: LCS 5410 ,,50
"Whiteboard, Music Staff Lines, 3 x 4, w/ 3 sets of 5 lines ",021.18.2.PC.01.8546,385.00,Model: Claridge LCS2034 ,,40
"Whiteboard, Music Staff Lines, on wheels, 3 x 4, aluminum frame ",021.18.2.PC.01.8546,650.00,"Model: Claridge LCS 155, casters add one ",,
"Whiteboard, Music Staff Lines, 4 x 6, Magnetic ",021.18.2.PC.01.8546,659.30,Model: Best Rite 202AG-S1 ,,40
"Whiteboard, Music Staff Lines, 4 x 8, Magnetic ",021.18.2.PC.01.8546,743.10,Model: Best Rite 202AH-S1 ,,40
"Workstation, Adjustable Height, 24-32"" adj legs, 24 x 60"" ",021.18.2.PC.01.8546,344.90,Model: Abco CFLAA2460 ,,
"Workstation, Adjustable Height, 24-32"" adj legs, 24 x 72"" ",021.18.2.PC.01.8546,375.00,Model: Abco CFLAA2472 ,,
"Casters for workstations above, 3"" ",021.18.2.PC.01.8546,38.10,"Model: Abco ""C"" ",,
CSV;

    return $csv;
  }

  /**
   * Discounted Line Item example CSV.
   *
   * @return string
   */
  private function discountedLineItemCsv() {
    $csv = <<<'CSV'
title,award,discount,description,exceptions
Fitness Eauioment,021.18.2.PC.01.7543,10,,
Track & Field,021.18.2.PC.01.7543,10,,
Activity Balls,021.18.2.PC.01.7542,32,,
Archery,021.18.2.PC.01.7542,32,,
Badminton,021.18.2.PC.01.7542,32,,
Basketball,021.18.2.PC.01.7542,32,,
Bowling,021.18.2.PC.01.7542,32,,
Equipment Carriers,021.18.2.PC.01.7542,32,,
Fencing,021.18.2.PC.01.7542,32,,
Fitness Equipment,021.18.2.PC.01.7542,32,,
Fitness Tracking Devices,021.18.2.PC.01.7542,32,,
Frisbee (Ultimate),021.18.2.PC.01.7542,32,,
Football,021.18.2.PC.01.7542,32,,
Golf,021.18.2.PC.01.7542,32,,
Gymnastic/Tumbling,021.18.2.PC.01.7542,32,,
"Hockey, Field",021.18.2.PC.01.7542,32,,
"Hockey, Floor",021.18.2.PC.01.7542,32,,
Lacrosse,021.18.2.PC.01.7542,32,,
Pickle Ball,021.18.2.PC.01.7542,32,,
Recreational Games Rhythms & Dance,021.18.2.PC.01.7542,32,,
Scooters,021.18.2.PC.01.7542,32,,
Soccer,021.18.2.PC.01.7542,32,,
Softball/VViffleball,021.18.2.PC.01.7542,32,,
Table Tennis,021.18.2.PC.01.7542,32,,
Tchoukball,021.18.2.PC.01.7542,32,,
Teaching Aids,021.18.2.PC.01.7542,32,,
Team Handball/Handball,021.18.2.PC.01.7542,32,,
Tennis,021.18.2.PC.01.7542,32,,
Track & Field,021.18.2.PC.01.7542,32,,
Volleyball,021.18.2.PC.01.7542,32,,
Miscellaneous,021.18.2.PC.01.7542,32,,
Activity Balls,021.18.2.PC.01.7546,18,,
Archery,021.18.2.PC.01.7546,18,,
Badminton,021.18.2.PC.01.7546,18,,
Basketball,021.18.2.PC.01.7546,18,,
Bowling,021.18.2.PC.01.7546,18,,
Equipment Carriers,021.18.2.PC.01.7546,18,,
Fencing,021.18.2.PC.01.7546,18,,
Fitness Equipment,021.18.2.PC.01.7546,18,,
Fitness Tracking Devices,021.18.2.PC.01.7546,18,,
Frisbee (Ultimate),021.18.2.PC.01.7546,18,,
Football,021.18.2.PC.01.7546,18,,
Golf,021.18.2.PC.01.7546,18,,
Gymnastic/Tumbling,021.18.2.PC.01.7546,18,,
"Hockey, Field",021.18.2.PC.01.7546,18,,
"Hockey, Floor",021.18.2.PC.01.7546,18,,
Lacrosse,021.18.2.PC.01.7546,18,,
Pickle Ball,021.18.2.PC.01.7546,18,,
Recreational Games Rhythms & Dance,021.18.2.PC.01.7546,18,,
Scooters,021.18.2.PC.01.7546,18,,
Soccer,021.18.2.PC.01.7546,18,,
Softball/VViffleball,021.18.2.PC.01.7546,18,,
Table Tennis,021.18.2.PC.01.7546,18,,
Tchoukball,021.18.2.PC.01.7546,18,,
Teaching Aids,021.18.2.PC.01.7546,18,,
Team Handball/Handball,021.18.2.PC.01.7546,18,,
Tennis,021.18.2.PC.01.7546,18,,
Track & Field,021.18.2.PC.01.7546,18,,
Volleyball,021.18.2.PC.01.7546,18,,
Miscellaneous,021.18.2.PC.01.7546,18,,
Activity Balls,021.18.2.PC.01.7545,20,,
Archery,021.18.2.PC.01.7545,20,,
Badminton,021.18.2.PC.01.7545,20,,
Basketball,021.18.2.PC.01.7545,20,,
Bowling,021.18.2.PC.01.7545,20,,
Equipment Carriers,021.18.2.PC.01.7545,20,,
Fitness Equipment,021.18.2.PC.01.7545,20,,
Frisbee (Ultimate),021.18.2.PC.01.7545,20,,
Football,021.18.2.PC.01.7545,20,,
Golf,021.18.2.PC.01.7545,20,,
Gymnastic/Tumbling,021.18.2.PC.01.7545,20,,
"Hockey, Floor",021.18.2.PC.01.7545,20,,
Lacrosse,021.18.2.PC.01.7545,20,,
Pickle Ball,021.18.2.PC.01.7545,20,,
Recreational Games Rhythms & Dance,021.18.2.PC.01.7545,20,,
Scooters,021.18.2.PC.01.7545,20,,
Soccer,021.18.2.PC.01.7545,20,,
Softball/VViffleball,021.18.2.PC.01.7545,20,,
Table Tennis,021.18.2.PC.01.7545,20,,
Tchoukball,021.18.2.PC.01.7545,20,,
Teaching Aids,021.18.2.PC.01.7545,20,,
Team Handball/Handball,021.18.2.PC.01.7545,20,,
Tennis,021.18.2.PC.01.7545,20,,
Volleyball,021.18.2.PC.01.7545,20,,
Miscellaneous,021.18.2.PC.01.7545,20,,
Activity Balls,021.18.2.PC.01.7541,10,,
Archery,021.18.2.PC.01.7541,10,,
Badminton,021.18.2.PC.01.7541,10,,
Basketball,021.18.2.PC.01.7541,10,,
Bowling,021.18.2.PC.01.7541,10,,
Equipment Carriers,021.18.2.PC.01.7541,10,,
Fencing,021.18.2.PC.01.7541,10,,
Fitness Equipment,021.18.2.PC.01.7541,10,,
Fitness Tracking Devices,021.18.2.PC.01.7541,10,,
Frisbee (Ultimate),021.18.2.PC.01.7541,10,,
Football,021.18.2.PC.01.7541,10,,
Golf,021.18.2.PC.01.7541,10,,
Gymnastic/Tumbling,021.18.2.PC.01.7541,10,,
"Hockey, Field",021.18.2.PC.01.7541,10,,
"Hockey, Floor",021.18.2.PC.01.7541,10,,
Lacrosse,021.18.2.PC.01.7541,10,,
Pickle Ball,021.18.2.PC.01.7541,10,,
Recreational Games Rhythms & Dance,021.18.2.PC.01.7541,10,,
Scooters,021.18.2.PC.01.7541,10,,
Soccer,021.18.2.PC.01.7541,10,,
Softball/VViffleball,021.18.2.PC.01.7541,10,,
Table Tennis,021.18.2.PC.01.7541,10,,
Tchoukball,021.18.2.PC.01.7541,10,,
Teaching Aids,021.18.2.PC.01.7541,10,,
Team Handball/Handball,021.18.2.PC.01.7541,10,,
Tennis,021.18.2.PC.01.7541,10,,
Track & Field,021.18.2.PC.01.7541,10,,
Volleyball,021.18.2.PC.01.7541,10,,
Miscellaneous,021.18.2.PC.01.7541,10,,
Activity Balls,021.18.2.PC.01.7544,25,,
Archery,021.18.2.PC.01.7544,25,,
Badminton,021.18.2.PC.01.7544,25,,
Basketball,021.18.2.PC.01.7544,25,,
Bowling,021.18.2.PC.01.7544,25,,
Equipment Carriers,021.18.2.PC.01.7544,25,,
Fencing,021.18.2.PC.01.7544,25,,
Fitness Equipment,021.18.2.PC.01.7544,25,,
Fitness Tracking Devices,021.18.2.PC.01.7544,25,,
Frisbee (Ultimate),021.18.2.PC.01.7544,25,,
Football,021.18.2.PC.01.7544,25,,
Golf,021.18.2.PC.01.7544,25,,
Gymnastic/Tumbling,021.18.2.PC.01.7544,25,,
"Hockey, Field",021.18.2.PC.01.7544,25,,
"Hockey, Floor",021.18.2.PC.01.7544,25,,
Lacrosse,021.18.2.PC.01.7544,25,,
Pickle Ball,021.18.2.PC.01.7544,25,,
Recreational Games Rhythms & Dance,021.18.2.PC.01.7544,25,,
Scooters,021.18.2.PC.01.7544,25,,
Soccer,021.18.2.PC.01.7544,25,,
Softball/VViffleball,021.18.2.PC.01.7544,25,,
Table Tennis,021.18.2.PC.01.7544,25,,
Tchoukball,021.18.2.PC.01.7544,25,,
Teaching Aids,021.18.2.PC.01.7544,25,,
Team Handball/Handball,021.18.2.PC.01.7544,25,,
Tennis,021.18.2.PC.01.7544,25,,
Track & Field,021.18.2.PC.01.7544,25,,
Volleyball,021.18.2.PC.01.7544,25,,
Miscellaneous,021.18.2.PC.01.7544,25,,
CSV;

    return $csv;
  }

  /**
   * Award example CSV content.
   *
   * @return string
   */
  private function awardCsv() {
    $csv = <<<'CSV'
id,procurement_method.name,procurement_method.id,vendor,notes,ordering_instructions,reference
021.18.2.PC.01.7541,solicitation,027.17.84,Gopher Sport,"10% Discount off the most recently published Gopher catalog .
Discount does not apply to ""Only from Gopher"" (OFG)items.
Please reference the HCPSSBid #027.17.B4 and indicate the 10% catalog discount on all orders (P.O.)or to your customer care representative when placing your order .",,7782799229
021.18.2.PC.01.7542,solicitation,027.17.84,School Specialty Inc.,"<p>Catalog List Price less 32% on supply items in the current School Specialty catalogs listed below .*</p>
<p>Catalog List Price less 12% on furniture items in the current School Specialty Catalogs listed below .*</p>
<ul>
<li>School Specialty Education Essentials Catalog</li>
<li>School Specialty Art Education Catalog/ SAX</li>
<li>School Specialty Special Needs Catalog/ Abilitations</li>
<li>School Specialty Physical Education & Recreation Catalog/Sport time</li>
<li>School Specialty Earl Childhood Catalog/ Childcraft</li>
<li>School Specialty Furniture & Equipment Catalog</li>
<li>School Specialty Safety and Security/ Guardian</li>
</ul>
<p>*These catalogs may contain a limited number of items that are listed as ""Net Price"" and these items are not eligible for any discount. Also excluded is any catalog that bears notation: no other discounts apply.</p>
<p>Freight Terms: Parcel Item Orders ship free of charge. (9 prefix items) Truck/Freight Items ship free of charge (6 prefix Items)</p>",Please reference the HCPSSBid #027.17.84 and #7782799229 on all orders (P.O.) or to your Customer care representative when placing your order.,027.17.B4HOWARD
021.18.2.PC.01.7543,solicitation,027.17.84,"MFAC, LLC","10% discount applies to the most recently published Perform Better & Everything Track & Field catalog. Excludes, sales, pits, hurdles, pole vault poles, crossbars, timing systems, software, cardio equipment, weight equip , in-ground equipment, Items requiring special shipping or truck shipment by common carrier.",Please reference the HCPSSBid #027 .17.84 on all orders (P.O.) or to your Customer care representative when placing your order.,
021.18.2.PC.01.7544,solicitation,027.17.84,Pyramid School Products,"25% discount applies to most recent Champion Sports catalog.
$50.00 minimum order.",Please reference the HCPSSBid (#027.17 .B4 HOWARD) on all orders (P.O.) or to your Customer care representative when placing your order.,
021.18.2.PC.01.7545,solicitation,027.17.84,"S & S Worldwide, Inc.","20% off current list price at time of order placement . Not to be combined with sales prices, offer codes, internet specials, or quantity breaks . Free standard ground shipping on all orders. FOB/Drop ship items are not eligible for free shipping. Truck shipments are not eligible for free inside delivery/lift gate. Installation/assembly are not provided. If you do not have a loading dock for drop ship items for a truck order . Contact the customer service department for a quote for inside delivery and a truck with a lift gate . Restocking fees up to 20% may apply to drop ship items if returned: fees vary by manufacturer . Please reference the HCPSSBid {#027.17.B4%) on all orders (P.O.)
or to your Customer care representative when placing your order.",,027.17.84%
021.18.2.PC.01.7546,solicitation,027.17.84,"BSN Sports, Passon's Sports, & US Games","<p>18% off current catalog price with exceptions (see BSN & US Games Exception List). 18% discount cannot be applied to an item with a bid price . Free standard FOBdestination. Inside Delivery charges for all Truck shipments will be 17%of
The order amount. Non-standard shipping and handling will require additional charges. If you add freight to your purchase order, you will be billed for freight. Freight charges cannot and will not be reversed once processed.</p>
<p>Installation/assembly is not included, but may be available in your area for an additional charge. Contact order department for specifics.</p>",Please reference the HCPSSBid #027 .17.B4 and #3076396-2017 on all orders (P.O.) or to your Customer care representative when placing your order.,3076396-2017
021.18.2.PC.01.8546,solicitation,127.17.84,Glover,,,
021.18.2.PC.01.8547,solicitation,127.17.84,Douron,,,
CSV;

    return $csv;
  }
}
