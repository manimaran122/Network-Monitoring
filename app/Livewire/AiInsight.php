<?php

namespace App\Livewire;

use Livewire\Component;
use App\Ai\Agents\MonitorAnalyzer;
use Illuminate\Support\Str;

class AiInsight extends Component
{
    public $analysis = null;
    public $analyzing = false;

    public function analyze()
    {
        $this->analyzing = true;
        
        try {
            $agent = new MonitorAnalyzer();
            $response = $agent->prompt('Analyze the current monitoring system status and provide insights.');
            
            // Handle both Prism response object and raw string
            $text = is_object($response) && isset($response->text) 
                ? $response->text 
                : (string) $response;
            
            $this->analysis = Str::markdown($text);
        } catch (\Exception $e) {
            $this->analysis = '<div class="text-red-500">Error analyzing system: ' . $e->getMessage() . '</div>';
        }

        $this->analyzing = false;
    }

    public function render()
    {
        return view('livewire.ai-insight');
    }
}
