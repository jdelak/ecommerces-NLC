<?php
/**
 * SolideWebservices/Flexslider
 *
 * @category Magento2_Module
 * @package  Flexslider
 * @author   Solide Webservices <contact@solidewebservices.com>
 * @license  https://opensource.org/licenses/OSL-3.0 Open Software License 3.0
 * @version  2.1.2
 * @link     https://solidewebservices.com
 */

namespace SolideWebservices\Flexslider\Ui\Component\Listing\Column;

use Magento\Catalog\Helper\Image;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Thumbnail extends Column
{
    const ALT_FIELD = 'title';

    /**
     * Variable.
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Construct.
     *
     * @param ContextInterface      $context            Context.
     * @param UiComponentFactory    $uiComponentFactory UIComponentFactory.
     * @param Image                 $imageHelper        ImageHelper.
     * @param UrlInterface          $urlBuilder         UrlBuilder.
     * @param StoreManagerInterface $storeManager       StoreManager.
     * @param array                 $components         Compontents.
     * @param array                 $data               Data.
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Image $imageHelper,
        UrlInterface $urlBuilder,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->imageHelper = $imageHelper;
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source.
     *
     * @param array $dataSource Data Source.
     *
     * @return datasource[]
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $url = '';
                if ($item[$fieldName] != '') {
                    $url = $this->storeManager
                    ->getStore()
                    ->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                        ) . 'flexslider/thumbnails/' . $item[$fieldName];
                }
                if ($url) {
                    $item[$fieldName . '_src'] = $url;
                    $item[$fieldName . '_orig_src'] = $this->storeManager->
                        getStore()
                        ->getBaseUrl(
                            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                        ) . 'flexslider/' . $item[$fieldName];
                } else {
                    $item[$fieldName . '_src'] = 'data:image/png;base64,iVBORw0
                    KGgoAAAANSUhEUgAAAC8AAAAfCAYAAAB3XZQBAAAABmJLR0QA/wD/AP+gva
                    eTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH4AEeEwwPdVz3igAAA
                    hpJREFUWMPtl89q20AQxr+RVm5ASCg6pBgbDL7UcU51bg741rxBcg55rNCX
                    kI8pcdwHSKGlJ0NMihWDQywhMFWslXd7aK3GDW6p/9QK1YAOu4fl983Ozje
                    ij58+SzzTUPCMI4PP4DP4/xmeiKCqKogIirLe3LBlD5BSgogScM/z0Ww2wT
                    nHwUEd1WoVUsp0wUsp0W6/R7/fh2maODx8AyklHMdBHMcAgMvLNgzDRLFYS
                    FfZ+L6PbreLMAwxGAzQ6/UwHo8hhPiZGcbgum5yM6mBV1V1Zq1pGnRdn6lz
                    zjl2dytrKxtadLYhInQ6HdzcfIFt29jfr0FVVQRBgKurD+Cco1J5hVKphMl
                    kMiNaCLESQbTMYHZ93UUUjX+8AQCQiTAAEEIgn8/Dtu1kfXHRwtbWCzQaja
                    UFLPxg7++HaLVaCUC5XMbOzstEwDQGgztYloUgCHB+/g6+72Nvr7rpbiNAR
                    EmW45jj4eErfk2mEBMoigLHaSKO45X2fraqg1z3Fq57+2Rf13UUCkWEYQhN
                    09LlsFJKRFE0t37X1CVXA2+aJk5PT7C9bf1OYjrhGWNgjEFR1Dk3813gY/N
                    KDbzneTg7e4vhcDi3rDRNQ71eX7mAheENw0Aul0vcdQr6+BNCwLZtEBFqtd
                    c4Pj564swbMynG2B/nFiFE4rBEhNFoBM45LMvabKucTo9/05l0Xc/+pDL4D
                    D6Dz+D/XXwD5+HtOHFg+HwAAAAASUVORK5CYII=';
                    $item[$fieldName . '_orig_src'] = 'data:image/png;base64,iV
                    BORw0KGgoAAAANSUhEUgAAAR8AAADICAYAAAGmHNX4AAAAGXRFWHRTb2Z0d
                    2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAACJJJREFUeNpivHjpigMDAwMI
                    DwrAAnVM/WBxEBPDIAOjDhp10KiDRh1ELpgzZ+7AO+j27dtUcwhVHCQvL48
                    SOs+fPx9YB+3evQeFf+7ceYpDjOI0lJKSTPXanmxgZGSIEiKSkpIM3t5eA+
                    cgkAOoHUKjBeOog0YdNOqggQYAAcQI7Cg2DCYHsQymTuJoGhp10KiDRh005
                    B3069cveDdoUHQUFy1azPDly5fBFWUrVqykapeaqmmIGtE2vHMZNfpooPbQ
                    /9FyaNRBow4addCogxAAIAC79pICIAhFYdiBuKyc5LpalS3DNcURroOGJfj
                    gPxAUDYpPK7pe+1G8HFljxAACCCCACEAAAQTQHPGjLqzeh5zvuq9aiVoPSi
                    ntfO817uWAtNAf4+FCCPVYnQjahPa3A2ELIEFYB41ABGb7FlV1rZA6akb5G
                    UbJHrX3DOvRgrT1Szqlc/g9UO7gMw8QQAABBBBAABGAvuQRgJ07OGEQCKIA
                    mjJiDbF3U4yWsT2EESYHScwlsJPsGxAETz6ccQ9/t1zCtFqVS7xqMUCAACl
                    AgAABAgQIECAFCBAgQIAA/W51zQdF/ieDU8tyv7TW9vsq8buuX1Dsco3gVO
                    52TZx8psVOKg+7qlBdN9IeA5uRdJ2m67Pthgb61EY5h2KLfpwbMHSI893LV
                    5hFJYBeQVT5k1kHVQaa51upNU+5FlvXbb98QV8e3sOsg8wgQIAAIQAECBAg
                    QIAAAVKAAAECBAjQf9VDAPbu4ChhKAqgaBYsk1VqCHsoIDVAAdJfasACUgO
                    uUgMUwDyYYBwTx4gzoP+cVXThgrmYFxzft7sD7zAEhIAQEAgIASEgBAQCQk
                    AICAGBgBAQAkJAICAExIMtvATjYj9G27aX67Iss+1240UR0FXTNNnxeLp9v
                    VxWWV3Xl+vYbBUbroZi2VzsM4rzX3e7F9WkfAvb718/xBMOh7fb8a1xPSXi
                    IvGAxo6xjd8s/RLC2H8Vp02Pefa9WI/g33p+SX/ue2qRJTcDxS2s67pP348
                    5KM+Lb/2M9Xo1OS8Zov+5qTnmq9ln6HqrWz3V5mYzEAL6q2JgjtsXApqtKP
                    Ksqt4/A8IMNEt8HvTTWSaetmIYj6FcQAkZHkRxrzjho39sH54dIyBmS/VvZ
                    Z7CEBACQkAICASEgBAQAgIBISAEhIBAQAgIASEgEBD3OwvAzr0qJRAFABje
                    4JAwUdwHIElxE0mKFimSsGjRx9KiRR5An4EHwAKJRCJJouicnYFRWHTxFjz
                    fV9xBZIadf3bPBVx8tRm2Fj4P7TvxuHUhHsSDeEA8iAfxIB7EA+JBPIgH8Y
                    B4EA/iQTyIB8SDeBAP4gHxIB7Eg3gQD4gH8SAexAPiQTyIB/EgHhAPv2/HK
                    SjW6/WS5+dZftxqHSb1et1JWRH+D/NLjG98MpnkP9M0fff4fD5Pbm/v1p5f
                    q9WSTudUMTFfeYriuLq6XB4XhRNMp1O1xD7mKYpjUzCrRqORYgyY169GZVS
                    rVSdLPJutjoHK/k48ETg+Plp77Oysuzxut08K/y7MuDDbym9T/X4/qVQqSb
                    PZLHzOeDxOBoOn/GqTZQdKEc/vub6+iW49KLqpephZlR0gF3k7rQ/CetHDw
                    2N+vFhUFE9kM6ssy7Z+rRDNYrExRrYnlvFsN6757hVMPP9szFL2tjWbzaIP
                    J8qp+k8YDq00iwfxIJ4/t7tb3biijHg+1O128xXk1bUbxPOpxYwpbENsI03
                    3lBP7VL3s53jW47G7bsD8DeFWFzZWxcOXXFycJ43GfrTv3676Dwgrzvf3vX
                    x/LKaPb4gHty3Eg3gQD4gH8SAexIN4QDyIB/EgHhAP4kE8iAfxgHgQD+JBP
                    CAexIN4EA/iAfEgHsSDeEA8iAfxIB7EA+Lhb7wK0N7dK6URhQEYPpnJWEET
                    mtCECpvQxMomVrGJTazwbvRazDWYxjRSkYZMMqSBioo00EBlFT8yOv4AARS
                    C7PPMOBgDrLNOXnfPObuJu6GeXD0e2xWA31yA+ACIDyA+AOIDiA+A+ADiAy
                    A+gPgA4gMgPoD4AIgPID4A4gOID4D4AOIDID6A+ADiAyA+gPgAiA8gPgDiA
                    4gPgPgA4gMgPoD4AOIDID6A+ACIDyA+AAt4aRewiOFwmAaDwejzfD6fcrmc
                    nYL48PQuLy/T2dmX1Ov1pj6vWCym/f0PaWtry05DfPgbj3q9nlqt9p2vRyQ
                    qlUra2Xk38bXxumbz10zb6Xa76fT089X77Ux9TxCfDIgjlojCpCg1Go3Rx7
                    hgdDqdmcNzW7xfsfh6dCQE4xhw3nCNxveJ4RkXjPvP7fX6C2+72/3tB4D4s
                    JhS6c3Cr61U3tqBiE9WRQBmHfwtlUoPTpMKhUI6PPw01zZje0dHVYPOTPXi
                    x8/mydXjsV2x2WLs5uKiNhrjuS8Cs7f3fvQ4/TSqm2q1WhoMhmP/ftb3AfF
                    hLV2vITJYvdnMdmXA9YzWYwaP51UovErlcnnmo6D4Hs/Pv94Z8DZdLz48Yz
                    HbFeFZtYhITNHH0cvBwce1+/4QH1YQgXlEKJZ9uhOrpGPt0bjxJ8SHjKrXv
                    z3pLFWcfu3u7t78edqCR8SHDPvXtVuP0W63hYcb1vmwMpOm6BEfAPEBNpsx
                    n4wadwlETI3H7TPAkQ9Ls71dfjCr5UJQHPmwdHGUE7e8iBXEEaH4fNmL/WJ
                    b7XbLwDPik3UxrR6XNKxStVqd686IiA/PVCzyW7e1NbHoMD7iAtKI3zLXFi
                    E+/Md/6HG5RBxtrPJ0Z5Z7Q8f/eHF9r6Bpt/xgM7mlBmsnTsn6/d4onG5I5
                    sgHVsasWzaYagfEBxAfAPEBxAdAfADxARAfQHwAxAcQH0B8AMQHEB8A8QHE
                    B0B8APEBEB9AfADEBxAfQHwAxAcQHwDxAcQHQHwA8QEQH0B8AMQHEB9AfAD
                    EBxAfAPEBNsYfrhx/FPujNF0AAAAASUVORK5CYII=';
                }
                $item[$fieldName . '_alt'] = $this->getAlt($item) ?: '';
                $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                    'flexslider/slide/edit',
                    ['slide_id' => $item['slide_id']]
                );
            }
        }

        return $dataSource;
    }

    /**
     * Get Alt Row.
     *
     * @param array $row Row.
     *
     * @return string
     */
    protected function getAlt($row)
    {
        $altField = $this->getData('config/altField') ?: self::ALT_FIELD;
        return isset($row[$altField]) ? $row[$altField] : null;
    }
}
