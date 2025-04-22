<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

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
                'icon_color' => 'text-primary-600'
            ],
           
            [
                'title' => 'Product Entry',
                'icon' => 'heroicons:document',
                'link' => route('dataentry.index'),
                'icon_color' => 'text-success-600'
            ], 
            [
                'title' => 'Product Issue',
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
                'icon_color' => 'text-info-600'
            ],
            
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
                'icon_color' => 'text-success-main'
            ],
                     
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
                'icon_color' => 'text-secondary-600'
            ],

            [
                'title' => 'Master Data Entry',
                'icon' => 'heroicons:clipboard',
                'link' => route('coreinventory.primaryrecords'),
                'icon_color' => 'text-muted'
            ],   
            [
                'title' => 'Return Product Management',
                'icon' => 'simple-line-icons:vector',
                'link' => route('logistics.returnstatuslog'),
                'icon_color' => 'text-warning-main'
            ],
            
           
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
                'icon_color' => 'text-secondary-600'
            ],
            // Security
            [
                'title' => 'Security',
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
                'icon_color' => 'text-secondary-600'
            ],
            // [
            //     'title' => 'Chat',
            //     'icon' => 'bi:chat-dots',
            //     'link' => 'chat-message.php',
            //     'icon_color' => 'text-primary-600'
            // ],
            [
                'title' => 'System Monitoring',
                'icon' => 'icon-park-outline:trend-two', // You can replace this with any icon you prefer
                'link' => route('telescope'), // Laravel route to Telescope page
                'icon_color' => 'text-success-main',
            ]
            
        ];
        
        View::share('menuItems', $menuItems);

        return $next($request);
    }
}
