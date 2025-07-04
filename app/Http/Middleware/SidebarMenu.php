<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Illuminate\Support\Facades\Log; 

class SidebarMenu
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $menuItems = [
            [
                'title' => 'Dashboard',
                'icon' => 'solar:home-smile-angle-outline',
                'link' => route('dashboard'),
                'submenus' => [],
                'icon_color' => 'text-primary-600',
            ],
           

            // Admin, Invertory manager , Invertory Entry , Sales
            [
                'title' => 'Product Entry',
                'icon' => 'heroicons:document',
                'link' => route('dataentry.index'),
                'icon_color' => 'text-success-600',
                'visibility' => Auth::check() && (
                    Bouncer::is(Auth::user())->an('Inventory Manager') || 
                    Bouncer::is(Auth::user())->an('Inventory Entry') || 
                    Bouncer::is(Auth::user())->an('Sales') ||
                    Bouncer::is(Auth::user())->an('Admin')
                ),

            ], 

            // Invertory manager , Invertory Entry , Sales, Dircetor
            [
                'title' => 'Select Product',
                'icon' => 'hugeicons:invoice-03',
                'link' => 'javascript:void(0)',
                'submenus' => [
                    [
                        'name' => '',
                        'link' => 'javascript:void(0)', 
                        'subchild' => [
                            ['name' => 'Product List','page_id' => 8,'link' => route('logistics.invoicelist'),'icon_color' => 'text-warning-main'],
                            ['name' => 'Create Delivery Challan','page_id' => 9,'link' => route('logistics.invoiceconfirm'),'icon_color' => 'text-primary-600'],
                            ['name' => 'Print Delivery Challan','page_id' => 10,'link' => route('logistics.invoicedownload'),'icon_color' => 'text-danger-600']
                            // ['name' => 'Edit','page_id' => 11,'link' => 'invoice-edit.php','icon_color' => 'text-success-main']
                        ],
                    ],
                ],
                'icon_color' => 'text-info-600',
                'visibility' => Auth::check() && (
                    Bouncer::is(Auth::user())->an('Inventory Manager') || 
                    Bouncer::is(Auth::user())->an('Inventory Entry') || 
                    Bouncer::is(Auth::user())->an('Sales') || 
                    Bouncer::is(Auth::user())->an('Admin') 
                   
                ),
            ],


            // Barcode Section : Inventory Manager, Inventory Entry, Sales, Director
            [
                'title' => 'Scanning Section',
                'icon' => 'mdi:barcode',
                'link' => 'javascript:void(0)',
                'submenus' => [
                    [
                        'name' => '',
                        'link' => 'javascript:void(0)', 
                        'subchild' => [
                            [
                                'name' => 'Product List with Barcodes',
                                'page_id' => 19, 
                                'link' => route('barcode.index'),  
                                'icon_color' => 'text-primary-600'
                            ],
                            // [
                            //     'name' => 'Download Barcode',
                            //     'page_id' => 20, 
                            //     'link' => route('barcode.download'), 
                            //     'icon_color' => 'text-success-600'
                            // ],
                        ],
                    ],
                ],
                'icon_color' => 'text-purple-600',
                'visibility' => Auth::check() && (
                    Bouncer::is(Auth::user())->an('Inventory Manager') || 
                    Bouncer::is(Auth::user())->an('Inventory Entry') || 
                    Bouncer::is(Auth::user())->an('Admin')
                ),
            ],


            
            // All 
            [
                'title' => 'Inventory Report',
                'icon' => 'solar:pie-chart-outline',
                'link' => 'javascript:void(0)',
                'submenus' => [
                    [
                        'name' => '',
                        'link' => 'javascript:void(0)',
                        'subchild' => [
                            ['name' => 'Current Stock Report','page_id' => 20,'link' => route('reports.currentstocklevels'),'icon_color' => 'text-success-main'],
                            ['name' => 'Local Purcharse','page_id' => 21,'link' => route('reports.postockreport'),'icon_color' => 'text-primary-600'],
                            ['name' => 'Foreign Purcharse','page_id' => 22,'link' => route('reports.lcwisestock'),'icon_color' => 'text-danger-main'],
                            ['name' => 'Faulty Items','page_id' => 23,'link' => route('reports.defectiveitems'),'icon_color' => 'text-warning-main'],
                            // ['name' => 'Product Warranty Overview','page_id' => 24,'link' => route('reports.productwarranty'),'icon_color' => 'text-info-600'],
                            // ['name' => 'Revenue Summary','page_id' => 25,'link' => route('reports.revenuesummary'),'icon_color' => 'text-success-main']
                        ]
                    ],
                ],
                'icon_color' => 'text-success-main',
                'visibility' => Auth::check(),
            ],
               
            // Sale ,admin and inventory manager see this
            [
                'title' => 'Procurement Document',
                'icon' => 'heroicons:cloud-arrow-up',
                'link' => 'javascript:void(0)',
                'submenus' => [
                    [
                        'name' => '',
                        'link' => 'javascript:void(0)',
                        'subchild' => [
                            ['name' => 'L.P.Doc','page_id' => 17,'link' => route('upload.localFileUpload'),'icon_color' => 'text-primary-600'],
                            ['name' => 'Foreign Purchase Doc','page_id' => 18,'link' => route('upload.importFileUpload'),'icon_color' => 'text-success-main']
                        ]
                    ],
                ],
                'icon_color' => 'text-secondary-600',
                'visibility' => Auth::check() && (
                    Bouncer::is(Auth::user())->an('Inventory Entry') || 
                    Bouncer::is(Auth::user())->an('Admin') || 
                    Bouncer::is(Auth::user())->an('Sales') || 
                    Bouncer::is(Auth::user())->an('Inventory Manager')
                ),

            ],

            // Inventory Entry and Admin see this
            [
                'title' => 'Master Data Entry',
                'icon' => 'heroicons:clipboard',
                'link' => route('coreinventory.primaryrecords'),
                'icon_color' => 'text-muted',
                'visibility' => Auth::check() && (Bouncer::is(Auth::user())->an('Inventory Entry') || Bouncer::is(Auth::user())->an('Admin')),
            ],   

            // Inventory Manager and Admin see this
            [
                'title' => 'Return Product Management',
                'icon' => 'simple-line-icons:vector',
                'link' => route('logistics.returnstatuslog'),
                'icon_color' => 'text-warning-main',
                'visibility' => Auth::check() && (Bouncer::is(Auth::user())->an('Inventory Manager') || Bouncer::is(Auth::user())->an('Admin')),

            ],
            
            // All User see this
            [
                'title' => 'Inventory Management',
                'icon' => 'mingcute:storage-line',
                'link' => 'javascript:void(0)',
                'submenus' => [
                    [
                        'name' => '',
                        'link' => 'javascript:void(0)',
                        'subchild' => [
                            ['name' => 'In Stock', 'page_id' => 1, 'link' => route('logistics.instock'),'icon_color' => 'text-warning-main'],
                            ['name' => 'Delivery Challan', 'page_id' => 2, 'link' => route('logistics.deliverychallan'), 'icon_color' => 'text-primary-600'],
                            // ['name' => 'Gate Pass', 'page_id' => 3, 'link' => 'table-data.php', 'icon_color' => 'text-warning-main'],
                            ['name' => 'Returnable','page_id' => 4,'link' => route('logistics.returnable'),'icon_color' => 'text-danger-600'],
                            ['name' => 'Defective','page_id' => 5,'link' => route('logistics.defective'),'icon_color' => 'text-success-main'],
                            ['name' => 'Master Data','page_id' => 7,'link' => route('logistics.coredata'),'icon_color' => 'text-primary-600'],
                            ['name' => 'All Documents','page_id' => 6,'link' => route('logistics.alldocuments'),'icon_color' => 'text-danger-main'],
                        ] 
                    ],
                ],
                'icon_color' => 'text-secondary-600',
                 'visibility' => Auth::check(),

            ],

            // Admin can only
            [
                'title' => 'Download Document & Product Modification',
                'icon' => 'icon-park-outline:download',
                'link' => 'javascript:void(0)', 
                'icon_color' => 'text-success-main',
                'visibility' => Auth::check() && Bouncer::is(Auth::user())->an('Admin'),
                'submenus' => [
                    [
                        'name' => '', // Wrapper level
                        'link' => 'javascript:void(0)',
                        'subchild' => [
                            [
                                'name' => 'Download Procurement Document',
                                'page_id' => 1,
                                'link' => route('accessing'),
                                'icon_color' => 'text-primary-600'
                            ],
                            [
                                'name' => 'Modify Product Information',
                                'page_id' => 2,
                                'link' => route('upgrade.info'), 
                                'icon_color' => 'text-warning-main'
                            ]
                        ]
                    ]
                ]
            ],

            [
                'title' => 'User Management',
                'icon' => 'flowbite:users-group-outline',
                'link' => 'javascript:void(0)',
                'submenus' => [
                    [
                        'name' => '',
                        'link' => 'javascript:void(0)',
                        'subchild' => [
                            ['name' => 'Users List','page_id' => 26,'link' => route('user.list'),'icon_color' => 'text-primary-600'],
                            ['name' => 'Users Creation','page_id' => 27,'link' => route('user.create'),'icon_color' => 'text-success-main'],
                            ['name' => 'Role Assign','page_id' => 28,'link' => auth()->check() ? route('role.assignment', ['user' => auth()->id()]) : '#','icon_color' => 'text-danger-main'],
                            ['name' => 'View Profile','page_id' => 29,'link' => auth()->check() ? route('view.profile', ['user' => auth()->id()]) : '#','icon_color' => 'text-info-600']
                        ]
                    ],
                ],
                'icon_color' => 'text-secondary-600',
                'visibility' => Auth::check() && Bouncer::is(Auth::user())->an('Admin'),
            ],
            [
                'title' => 'System Monitoring',
                'icon' => 'icon-park-outline:trend-two', 
                'link' => route('telescope'),
                'icon_color' => 'text-success-main',
                'visibility' => Auth::check() && Bouncer::is(Auth::user())->an('Admin'),
            ]
            
        ];

        Log::info('Admin Role Check', ['admin_check' => Auth::check() ? Bouncer::is(Auth::user())->an('Admin') : 'User not authenticated']);
        
        // Filter out menu items based on roles/permissions
        $menuItems = $this->filterMenuItemsBasedOnRole($menuItems);
        View::share('menuItems', $menuItems);

        return $next($request);
    }
    /**
     * Filter the menu items based on user roles or permissions.
     *
     * @param array $menuItems
     * @return array
     */
    private function filterMenuItemsBasedOnRole(array $menuItems)
    {
        // Filter out menu items based on roles/permissions
        foreach ($menuItems as &$menuItem) {
            // If the item has 'visibility' set to false, we hide it
            if (isset($menuItem['visibility']) && !$menuItem['visibility']) {
                $menuItem = null; // Hide the menu item
            }

            // Optionally, filter submenus based on the same criteria
            if (isset($menuItem['submenus'])) {
                foreach ($menuItem['submenus'] as &$submenu) {
                    foreach ($submenu['subchild'] as &$subchild) {
                        // Check role-based visibility for subchild
                        if (isset($subchild['visibility']) && !$subchild['visibility']) {
                            $subchild = null;
                        }
                    }
                }
            }
        }

        // Remove null values (hidden menu items)
        return array_filter($menuItems, function ($menuItem) {
            return $menuItem !== null;
        });
    }
}
